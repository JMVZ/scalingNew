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
        const path = currentPath.value;
        
        // Buscar todos los items de todos los módulos que coinciden con la ruta actual
        const allMatchingItems = menuConfig.modules
            .flatMap(m => m.items.map(item => ({ ...item, moduleId: m.id })))
            .filter(item => {
                if (item.route === '/dashboard') {
                    return path === '/dashboard';
                }
                return path === item.route || path.startsWith(item.route + '/') || path.startsWith(item.route + '?');
            })
            .sort((a, b) => {
                // Primero por longitud de ruta (más específico primero)
                if (b.route.length !== a.route.length) {
                    return b.route.length - a.route.length;
                }
                // Si tienen la misma longitud, priorizar "datos-maestros"
                if (a.moduleId === 'datos-maestros' && b.moduleId !== 'datos-maestros') {
                    return -1;
                }
                if (b.moduleId === 'datos-maestros' && a.moduleId !== 'datos-maestros') {
                    return 1;
                }
                return 0;
            });
        
        if (allMatchingItems.length === 0) {
            return false;
        }
        
        // Solo activo si este módulo tiene el item con mayor prioridad
        const topItem = allMatchingItems[0];
        return topItem.moduleId === module.id;
    };

    const isItemActive = (item) => {
        const path = currentPath.value;

        // Para dashboard, comparación exacta
        if (item.route === '/dashboard') {
            return path === '/dashboard';
        }

        // Verificar si la ruta coincide
        const routeMatches = path === item.route || path.startsWith(item.route + '/') || path.startsWith(item.route + '?');
        
        if (!routeMatches) {
            return false;
        }

        // Buscar todos los items que coinciden con esta ruta
        const allMatchingItems = menuConfig.modules
            .flatMap(m => m.items.map(i => ({ ...i, moduleId: m.id })))
            .filter(i => {
                if (i.route === '/dashboard') {
                    return path === '/dashboard';
                }
                return path === i.route || path.startsWith(i.route + '/') || path.startsWith(i.route + '?');
            })
            .sort((a, b) => {
                // Primero por longitud de ruta (más específico primero)
                if (b.route.length !== a.route.length) {
                    return b.route.length - a.route.length;
                }
                // Si tienen la misma longitud, priorizar "datos-maestros"
                if (a.moduleId === 'datos-maestros' && b.moduleId !== 'datos-maestros') {
                    return -1;
                }
                if (b.moduleId === 'datos-maestros' && a.moduleId !== 'datos-maestros') {
                    return 1;
                }
                return 0;
            });
        
        // Solo activo si este item es el de mayor prioridad
        if (allMatchingItems.length === 0) {
            return false;
        }
        
        const topItem = allMatchingItems[0];
        return topItem.route === item.route && topItem.moduleId === menuConfig.modules.find(m => m.items.some(i => i.id === item.id))?.id;
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
