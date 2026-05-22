import { Link } from "@inertiajs/react";

export const EngineerBar = () => {
    const links = [
        { name: "Projetos de Vinícolas", route: "Enologia.Projetos.index" },
        { name: "Automação de Processos", route: "Enologia.Automacao.index" },
        // { name: "Consultoria", route: "Enologia.Consultoria.index" },
    ];

    return (
        <section className="bg-neutral-900 py-8 xl:py-12">
            <div className="container max-w-medium">
                <div className="grid max-sm:auto-rows-[1fr] grid-cols-1 gap-4 sm:grid-cols-2">
                    {links.map((link, idx) => (
                        <Link
                            key={idx}
                            href={route(link.route)}
                            className={`w-full px-3 py-1 md:py-3 font-secondary text-center text-2xl 2xl:text-3xl hover:bg-neutral-800 transition-all duration-200 ${
                                route().current(link.route)
                                    ? "font-bold text-eno-primary"
                                    : "text-white font-light"
                            }`}
                        >
                            {link.name}
                        </Link>
                    ))}
                </div>
            </div>
        </section>
    );
};
