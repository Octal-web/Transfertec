import React, { useState, useEffect } from 'react';
import { usePage, router } from '@inertiajs/react';

import DefaultLayout from '@/Layouts/EnologiaLayout';

import { NewsBanner } from '@/Components/Enologia/NewsBanner';
import { NewsList } from '@/Components/Enologia/NewsList';

const getInitialCategory = () => {
    const params = new URLSearchParams(window.location.search);
    return params.get('category') || 'todos';
};

const Page = () => {
    const { conteudos, postsCategorias, posts: initialPosts } = usePage().props;
    const [selectedCategory, setSelectedCategory] = useState(getInitialCategory);
    const [posts, setPosts] = useState(initialPosts);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        setLoading(false);
    }, []);

    const handlePageChange = (url) => {
        setLoading(true);

        router.visit(url, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ['posts'],
            onSuccess: (page) => {
                setPosts(page.props.posts);
                setLoading(false);
            }
        });
    };

    return (
        <DefaultLayout>
            <NewsBanner
                content={conteudos[0]}
                categories={postsCategorias}
                selectedCategory={selectedCategory}
                setSelectedCategory={setSelectedCategory}
                onCategoryChange={handlePageChange}
            />

            <NewsList
                posts={posts.data}
                loading={loading}
                links={posts.links} 
                totalPages={posts.last_page}
                onPageChange={handlePageChange}
            />

        </DefaultLayout>
    );
};

export default Page;