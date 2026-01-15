<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Operador;
use App\Models\OrdenCarga;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear empresa demo
        $empresa = Empresa::create([
            'nombre' => 'Transportes Demo S.A. de C.V.',
            'rfc' => 'TDE010101AAA',
            'email' => 'contacto@transportesdemo.com',
            'telefono' => '5555555555',
            'direccion' => 'Av. Principal #123, Col. Centro, CDMX',
            'activo' => true,
        ]);

        // Crear usuario demo
        $usuario = User::create([
            'name' => 'Admin Demo',
            'email' => 'admin@demo.com',
            'password' => Hash::make('password'),
            'empresa_id' => $empresa->id,
        ]);

        // Crear operadores demo (FASE 1)
        $operadores = [
            [
                'numero_operador' => 'OP-001',
                'activo' => true,
                'fecha_contratacion' => '2024-01-15',
                'apellido_paterno' => 'Pérez',
                'apellido_materno' => 'López',
                'nombres' => 'Juan',
                'telefono' => '5551234567',
                'correo' => 'juan.perez@email.com',
                'licencia' => 'A1234567',
                'vencimiento_licencia' => '2026-01-15',
            ],
            [
                'numero_operador' => 'OP-002',
                'activo' => true,
                'fecha_contratacion' => '2024-02-01',
                'apellido_paterno' => 'González',
                'apellido_materno' => 'Ruiz',
                'nombres' => 'María',
                'telefono' => '5559876543',
                'correo' => 'maria.gonzalez@email.com',
                'licencia' => 'B9876543',
                'vencimiento_licencia' => '2026-02-01',
            ],
        ];

        foreach ($operadores as $operadorData) {
            Operador::create(array_merge($operadorData, [
                'empresa_id' => $empresa->id,
            ]));
        }

        // Crear clientes demo (FASE 1)
        $clientes = [
            [
                'numero_cliente' => 'CLI-001',
                'nombre_fiscal' => 'Coca-Cola FEMSA S.A. de C.V.',
                'nombre_comercial' => 'Coca-Cola',
                'rfc' => 'CCF990101XXX',
                'estatus' => 'activo',
                'fecha_alta' => '2024-01-10',
                'direccion' => 'Av. Insurgentes Sur 1234, CDMX',
                'telefono' => '5556667777',
                'correo' => 'contacto@coca-cola.com',
            ],
            [
                'numero_cliente' => 'CLI-002',
                'nombre_fiscal' => 'Liverpool S.A. de C.V.',
                'nombre_comercial' => 'Liverpool',
                'rfc' => 'LIV850101XXX',
                'estatus' => 'activo',
                'fecha_alta' => '2024-01-12',
                'direccion' => 'Av. Reforma 456, CDMX',
                'telefono' => '5557778888',
                'correo' => 'contacto@liverpool.com',
            ],
            [
                'numero_cliente' => 'CLI-003',
                'nombre_fiscal' => 'CEMEX S.A.B. de C.V.',
                'nombre_comercial' => 'CEMEX',
                'rfc' => 'CEM750101XXX',
                'estatus' => 'activo',
                'fecha_alta' => '2024-01-15',
                'direccion' => 'Blvd. Industrial 789, Monterrey, NL',
                'telefono' => '5558889999',
                'correo' => 'contacto@cemex.com',
            ],
        ];

        foreach ($clientes as $clienteData) {
            Cliente::create(array_merge($clienteData, [
                'empresa_id' => $empresa->id,
            ]));
        }

        $this->command->info('✅ Datos demo creados exitosamente');
        $this->command->info('📧 Email: admin@demo.com');
        $this->command->info('🔑 Password: password');
        $this->command->info('🏢 Empresa: ' . $empresa->nombre);
        $this->command->info('👥 Clientes creados: ' . count($clientes));
        $this->command->info('🚚 Operadores creados: ' . count($operadores));
    }
}
