import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import menuConfig from '../config/menu.json';

export function useMenu() {
    const page = usePage();
    const openModules = ref(new Set());
    const currentPath = computed(() => page.url);

    const toggleModule = (moduleId) => {
        if (openModules.value.has(moduleId)) {
            // Si el módulo está abierto, cerrarlo
            openModules.value.delete(moduleId);
        } else {
            // Cerrar todos los demás módulos (comportamiento de acordeón)
            openModules.value.clear();
            // Abrir solo el módulo seleccionado
            openModules.value.add(moduleId);
        }
    };

    const closeModule = (moduleId) => {
        openModules.value.delete(moduleId);
    };

    const closeAllModules = () => {
        openModules.value.clear();
    };

    const isModuleOpen = (moduleId) => {
        return openModules.value.has(moduleId);
    };

    const isModuleActive = (module) => {
        return module.items.some(item => currentPath.value.startsWith(item.route));
    };

    const isItemActive = (item) => {
        const path = currentPath.value;

        // Para dashboard, comparación exacta
        if (item.route === '/dashboard') {
            return path === '/dashboard';
        }

        // Comparación exacta primero
        if (path === item.route) {
            return true;
        }

        // Si la ruta actual empieza con la ruta del item
        if (path.startsWith(item.route)) {
            // Buscar el módulo al que pertenece este item
            const module = menuConfig.modules.find(m => m.items.some(i => i.id === item.id));
            if (module) {
                // Verificar si hay otro item en el mismo módulo con una ruta más específica que también coincida
                const moreSpecificItem = module.items.find(i =>
                    i.id !== item.id &&
                    path.startsWith(i.route) &&
                    i.route.length > item.route.length
                );
                // Solo activo si no hay un item más específico
                return !moreSpecificItem;
            }
            return true;
        }

        return false;
    };

    // Detectar módulo activo basado en la ruta actual
    const getActiveModule = () => {
        return menuConfig.modules.find(module =>
            module.items.some(item => currentPath.value.startsWith(item.route))
        );
    };

    return {
        menuConfig,
        openModules,
        toggleModule,
        closeModule,
        closeAllModules,
        isModuleOpen,
        isModuleActive,
        isItemActive,
        getActiveModule,
        currentPath
    };
}
