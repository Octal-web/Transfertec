import { useEffect, useState, useRef } from "react";
import { Link } from "@inertiajs/react";

export const MenuItem = ({ item, controller, isSubMenuOpen, onToggleSubMenu }) => {
    const menuRef = useRef(null);
    const toggleRef = useRef(null);
    const mainButtonRef = useRef(null); // Nova ref para o botão principal

    useEffect(() => {
        function handleClickOutside(event) {
            if (
                menuRef.current &&
                !menuRef.current.contains(event.target) &&
                toggleRef.current &&
                !toggleRef.current.contains(event.target) &&
                // Verifica se o clique não foi no botão principal (quando é button)
                !(mainButtonRef.current && mainButtonRef.current.contains(event.target))
            ) {
                if (isSubMenuOpen) {
                    onToggleSubMenu();
                }
            }
        }

        if (isSubMenuOpen) {
            document.addEventListener("mousedown", handleClickOutside);
        }
        
        return () => {
            document.removeEventListener("mousedown", handleClickOutside);
        };
    }, [isSubMenuOpen, onToggleSubMenu]);

    return (
        <li className="relative">
            {item.external ? (
                <a
                    href={item.controller}
                    className="relative font-secondary block max-sm:text-xl text-white transition-opacity hover:opacity-70 xl:tracking-tight 2xl:tracking-normal"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    {item.name}
                </a>
            ) : item.submenu && item.submenu.length > 0 ? (
                <div className="flex items-center">
                    {item.route ? (
                        <Link
                            href={route(item.route)}
                            className={`relative font-secondary text-white transition-opacity hover:opacity-70 xl:tracking-tight 2xl:tracking-normal p-2${
                                controller === item.controller
                                    ? ` text-[0] before:content-[attr(data-before)] before:text-lg sm:before:text-base before:font-bold`
                                    : ' max-sm:text-lg'
                            }`}
                            data-before={item.name}
                        >
                            {item.name}
                        </Link>
                    ) : (
                        <button
                            ref={mainButtonRef} // Adiciona ref ao botão principal
                            onClick={onToggleSubMenu}
                            className={`relative font-secondary text-white transition-opacity hover:opacity-70 xl:tracking-tight 2xl:tracking-normal p-2${
                                controller === item.controller
                                    ? ` text-[0] before:content-[attr(data-before)] before:text-lg sm:before:text-base before:font-bold`
                                    : ' max-sm:text-lg'
                            }`}
                            data-before={item.name}
                        >
                            {item.name}
                        </button>
                    )}
                    <button
                        ref={toggleRef}
                        onClick={onToggleSubMenu}
                        className="ml-2 text-white text-base max-sm: hover:opacity-70 transition-transform"
                        aria-label="Abrir submenu"
                    >
                        <span className="max-md:hidden">{isSubMenuOpen ? "▲" : "▼"}</span>
                        <span className="md:hidden text-3xl -translate-y-0.5 block">+</span>
                    </button>
                </div>
            ) : (
                <Link
                    href={route(item.route)}
                    className={`relative font-secondary block text-white transition-opacity hover:opacity-70 xl:tracking-tight 2xl:tracking-normal p-2${
                        controller === item.controller
                            ? ` text-[0] before:content-[attr(data-before)] before:text-lg sm:before:text-base before:font-bold`
                            : ' max-sm:text-xl'
                    }`}
                    data-before={item.name}
                >
                    {item.name}
                </Link>
            )}

            {item.submenu && item.submenu.length > 0 && (
                <ul
                    ref={menuRef}
                    className={`fixed md:absolute max-md:bottom-0 -right-10 md:-right-10 md:-left-10 mt-2 w-full md:w-56 max-md:h-full max-md:flex max-md:flex-col max-md:pl-10 bg-eno-primary md:bg-white shadow-lg md:rounded-lg overflow-hidden transition-all z-[1] ${
                        isSubMenuOpen ? "md:max-h-96 md:opacity-100" : "md:max-h-0 md:opacity-0 max-md:translate-x-full"
                    }`}
                >
                    <li className="md:hidden text-white text-2xl font-medium mt-40 mb-4">
                        Produtos e Serviços
                    </li>
                    {item.submenu.map((subItem, index) => (
                        <li key={index} className="md:border-b md:border-gray-200 last:border-0">
                            <Link
                                href={subItem.route ? route(subItem.route) : `${route(item.route)}#${subItem.slug}`}
                                className="block md:px-4 py-2 text-gray-700 max-md:text-white md:hover:bg-gray-100"
                                onClick={() => {
                                    if (isSubMenuOpen) {
                                        onToggleSubMenu();
                                    }
                                }}
                            >
                                {subItem.name ?? subItem.slug}
                            </Link>
                        </li>
                    ))}
                </ul>
            )}
        </li>
    );
};