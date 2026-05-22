import React, { useState, useEffect } from 'react';
import { usePage, router } from '@inertiajs/react';

import DefaultLayout from '@/Layouts/EngenhariaLayout';

import { PostItem } from '@/Components/Engenharia/PostItem';
import { PostOthers } from '@/Components/Engenharia/PostOthers';

const Page = () => {
    const { post, posts } = usePage().props;
    return (
        <DefaultLayout>
            <PostItem post={post} />
            <PostOthers posts={posts} />
        </DefaultLayout>
    );
};

export default Page;
