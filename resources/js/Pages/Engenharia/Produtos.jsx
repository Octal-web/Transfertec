import { usePage } from '@inertiajs/react';

import DefaultLayout from '@/Layouts/EngenhariaLayout';

// import { ProductBanner } from '@/Components/Engenharia/ProductBanner';
import { ProductList } from '@/Components/Engenharia/ProductList';
import { ProductItem } from '@/Components/Engenharia/ProductItem';
import { ProductCTA } from '@/Components/Engenharia/ProductCTA';
import { HomePosts } from '@/Components/Engenharia/HomePosts';

const Page = () => {
    const { produtos, posts, conteudos } = usePage().props;
    return (
        <DefaultLayout>
            {/* <ProductBanner content={conteudos[0]} /> */}
            <ProductList products={produtos} />
            {produtos.map((produto, index) => (
                <ProductItem
                    key={index}
                    product={produto}
                    index={index}
                    contents={[1, 3].includes(index) && conteudos}
                    reverse={index % 2 === 1}
                />
            ))}
            <ProductCTA />
            <HomePosts posts={posts} />
        </DefaultLayout>
    );
};

export default Page;
