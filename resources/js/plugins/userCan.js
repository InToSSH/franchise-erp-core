export default {
    install(app) {
        const resolveAbilities = () => {
            // prefer Inertia-provided $page if available, fallback to global window.page
            const page = app.config.globalProperties.$page ?? window.page;
            return page?.props?.auth?.user?.abilities_list ?? [];
        };

        const can = (ability) => {
            if (!ability) return false;
            const abilities = resolveAbilities();

            // abilities may be array of strings or array of objects with `name`
            return abilities.some((a) =>
                typeof a === 'string' ? a === ability : a?.name === ability
            );
        };

        // expose both `userCan` and `$userCan`
        app.config.globalProperties.userCan = can;
        app.config.globalProperties.$userCan = can;

        // also provide for `inject()` usage if needed
        app.provide('userCan', can);
    },
};
