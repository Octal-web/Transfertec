import React, { useState } from 'react';

export const EquipmentCategories = ({ categories, selectedCategory, setSelectedCategory, selectedSubcategory, setSelectedSubcategory, loading }) => {
    const [mobileView, setMobileView] = useState('categories');
    
    const handleCategoryClick = (category) => {
        if (selectedCategory?.id === category.id) return;
        setSelectedCategory(category);
        if (category.subcategorias?.length > 0) {
            setSelectedSubcategory(category.subcategorias[0]);
            if (category.subcategorias.length > 1 && category.subcategorias[0]?.nome !== 'Todos') {
                setMobileView('subcategories');
            }
        } else {
            setSelectedSubcategory(null);
        }
    };

    const handleSubcategoryClick = (subcategory) => {
        setSelectedSubcategory(subcategory);
        if (subcategory?.slug) {
            const section = document.getElementById(subcategory.slug);
            if (section) {
                section.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    };

    const handleBackToCategories = () => {
        setMobileView('categories');
    };

    if (loading) {
        return (
            <div className="col-span-7 text-white p-6">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-x-10 animate-pulse">
                    {[...Array(12)].map((_, i) => (
                        <div key={i} className="h-10 bg-neutral-700 rounded mb-4"></div>
                    ))}
                </div>
            </div>
        );
    }

    return (
        <div className="md:col-span-7 flex max-md:pt-24">
            <div className="hidden md:flex w-full">
                <div className="w-1/2 py-4 px-6 p-6 border-r border-gray-700">
                    <nav className="2xl:space-y-1">
                        {categories?.map((category) => (
                            <div key={category.id}>
                                <button
                                    onClick={() => handleCategoryClick(category)}
                                    className={`w-full px-3 py-3 font-secondary text-left text-2xl 2xl:text-3xl hover:bg-neutral-800 transition-all duration-200 ${
                                        selectedCategory?.id === category.id ? 'font-bold text-eno-primary' : 'text-white font-light'
                                    }`}
                                >
                                    {category.nome}
                                </button>
                            </div>
                        ))}
                    </nav>
                </div>
                
                {selectedCategory && selectedCategory.subcategorias && selectedCategory.subcategorias.length > 0 ? (
                    <div className="w-1/2 p-6 flex flex-col">
                        <nav className="space-y-1">
                            {selectedCategory.subcategorias.map((subcategory) => (
                                <button
                                    key={subcategory.id}
                                    onClick={() => handleSubcategoryClick(subcategory)}
                                    className="w-full text-left px-3 py-2 text-gray-400 hover:text-white text-lg hover:bg-neutral-800 transition-colors duration-200"
                                >
                                    {subcategory.nome}
                                </button>
                            ))}
                        </nav>
                    </div>
                ) : null}
            </div>

            <div className="md:hidden w-full">
                {mobileView === 'categories' ? (
                    <div className="p-4">
                        <nav className="space-y-2 pt-10">
                            {categories?.map((category) => (
                                <div key={category.id}>
                                    <button
                                        onClick={() => handleCategoryClick(category)}
                                        className={`w-full px-4 py-2 font-secondary text-left text-white text-xl hover:bg-neutral-800 transition-all duration-200 rounded-lg flex items-center justify-between ${
                                            selectedCategory?.id === category.id ? 'font-bold text-eno-primary bg-neutral-800' : 'font-light'
                                        }`}
                                    >
                                        <span>{category.nome}</span>
                                        {Array.isArray(category.subcategorias) &&
                                            (
                                                category.subcategorias.length > 1 ||
                                                (category.subcategorias.length === 1 && category.subcategorias[0]?.nome !== 'Todos')
                                            ) && (
                                                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                                </svg>
                                            )
                                        }
                                    </button>
                                </div>
                            ))}
                        </nav>
                    </div>
                ) : (
                    <div className="p-4">
                        <div className="flex items-center mb-4 pb-4 border-b border-gray-700">
                            <button
                                onClick={handleBackToCategories}
                                className="flex items-center text-gray-400 hover:text-white transition-colors duration-200"
                            >
                                <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                                </svg>
                                Voltar
                            </button>
                            <h3 className="ml-4 text-xl font-bold text-eno-primary">
                                {selectedCategory?.nome}
                            </h3>
                        </div>

                        {selectedCategory && selectedCategory.subcategorias && (
                            <nav className="md:space-y-2 mb-6">
                                {selectedCategory.subcategorias.map((subcategory) => (
                                    <button
                                        key={subcategory.id}
                                        onClick={() => handleSubcategoryClick(subcategory)}
                                        className="w-full text-left px-4 py-3 text-gray-400 hover:text-white text-lg hover:bg-neutral-800 transition-colors duration-200 rounded-lg"
                                    >
                                        {subcategory.nome}
                                    </button>
                                ))}
                            </nav>
                        )}
                    </div>
                )}
            </div>
        </div>
    );
};