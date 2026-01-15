<?php

namespace App\Http\Requests\Auth;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rfc' => ['required', 'string', 'exists:empresas,rfc'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'rfc.required' => 'El RFC de la empresa es obligatorio',
            'rfc.exists' => 'El RFC de la empresa no existe en el sistema',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El correo electrónico debe ser válido',
            'password.required' => 'La contraseña es obligatoria',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $rfc = strtoupper($this->input('rfc'));
        $email = $this->input('email');
        $password = $this->input('password');

        // 1. Buscar la empresa por RFC
        $empresa = Empresa::where('rfc', $rfc)->first();

        if (!$empresa) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'rfc' => 'El RFC de la empresa no existe en el sistema',
            ]);
        }

        // 2. Buscar usuario por email que pertenezca a esa empresa
        $user = User::where('email', $email)
            ->where('empresa_id', $empresa->id)
            ->first();

        // 3. Verificar que el usuario existe y la contraseña es correcta
        if (!$user || !Hash::check($password, $user->password)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Las credenciales no coinciden con nuestros registros para esta empresa.',
            ]);
        }

        // 4. Autenticar al usuario
        Auth::login($user, $this->boolean('remember'));
        
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('rfc').'|'.$this->string('email')).'|'.$this->ip());
    }
}
