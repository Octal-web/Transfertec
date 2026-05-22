import React, { useState, useEffect } from 'react';
import { usePage, router } from '@inertiajs/react';

import DefaultLayout from '@/Layouts/EnologiaLayout';

import { PostItem } from '@/Components/Enologia/PostItem';
import { PostOthers } from '@/Components/Enologia/PostOthers';

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
