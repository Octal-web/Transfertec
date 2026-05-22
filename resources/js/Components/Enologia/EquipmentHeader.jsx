import { EquipmentCategories } from './EquipmentCategories';

export const EquipmentHeader = ({ content, categories, selectedCategory, setSelectedCategory, selectedSubcategory, setSelectedSubcategory, loading }) => {
    return (
        <div className="relative container max-w-large">
            <div className="md:grid md:grid-cols-12">
                <div className="md:col-span-5 my-auto max-md:pb-20 max-md:translate-y-24">
                    <h1 className="text-4xl 2xl:text-5xl text-white max-w-md mb-4 md:mb-8">{content.titulo}</h1>
                    <div className="font-secondary text-white max-w-lg text-balance" dangerouslySetInnerHTML={{ __html: content.texto }} />
                </div>

                <EquipmentCategories
                    categories={categories}
                    selectedCategory={selectedCategory}
                    setSelectedCategory={setSelectedCategory}
                    selectedSubcategory={selectedSubcategory}
                    setSelectedSubcategory={setSelectedSubcategory}
                    loading={loading}
                />
            </div>
        </div>
    );
};