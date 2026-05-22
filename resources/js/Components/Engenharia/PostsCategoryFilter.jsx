import React from 'react';
import { router } from '@inertiajs/react';

export const PostsCategoryFilter = ({ categories, selectedCategory, setSelectedCategory, onCategoryChange }) => {
    const handleCategoryClick = (category) => {
        
        const currentParams = new URLSearchParams(window.location.search);
        
        currentParams.delete('page');
        
        if (category === 'todos') {
            currentParams.delete('categoria');
        } else {
            currentParams.set('categoria', category);
        }

        setSelectedCategory(category);
        
        const newUrl = window.location.pathname + (currentParams.toString() ? '?' + currentParams.toString() : '');
        
        onCategoryChange(newUrl);
    };

    return (
        <div className="flex items-center gap-4 flex-wrap">
            <span className="font-secondary text-eng-tertiary">Classificar por:</span>
            <div className="flex gap-2 flex-wrap">
                <button
                    onClick={() => handleCategoryClick('todos')}
                    className={`px-4 py-2 rounded-xl font-secondary text-sm font-medium border transition-all ${
                        selectedCategory === 'todos' || !selectedCategory
                            ? 'bg-eng-primary text-white border-eng-primary'
                            : ' text-neutral-600 border-neutral-600 hover:bg-gray-200'
                    }`}
                >
                    Todos
                </button>
                
                {categories.map((category, index) => (
                    <button
                        key={index}
                        onClick={() => handleCategoryClick(category.slug)}
                        className={`px-4 py-2 rounded-xl font-secondary text-sm font-medium border transition-all ${
                            selectedCategory === category.slug
                                ? 'bg-eng-primary text-white border-eng-primary'
                                : 'text-neutral-600 border-neutral-600 hover:bg-gray-200'
                        }`}
                    >
                        {category.nome}
                    </button>
                ))}
            </div>
        </div>
    );
};