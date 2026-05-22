import { YeastCategories } from './YeastCategories';

export const YeastHeader = ({ content, categories, selectedCategory, setSelectedCategory, selectedSubcategory, setSelectedSubcategory, loading }) => {
    return (
        <div className="relative container max-w-large">
            <div className="md:grid md:grid-cols-12">
                <div className="md:col-span-5 max-md:my-auto md:mt-10 max-md:pb-20">
                    <h1 className="text-4xl 2xl:text-5xl text-white max-w-md mb-8">{content.titulo}</h1>
                    <div className="font-secondary text-white max-w-lg" dangerouslySetInnerHTML={{ __html: content.texto }} />
                </div>

                <YeastCategories
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