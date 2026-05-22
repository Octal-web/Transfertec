import React from 'react';
import { Head } from '@inertiajs/react';

const AuthEnologiaLayout = ({ children }) => {
    
    return (
        <>
            <Head>
                <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0, shrink-to-fit=no"/>
                <meta charset="utf-8"/>
                <title>Transfertec | Acesso</title>
                <link rel="icon" href={`/eno/favicon.ico`} type="image/x-icon" />
                <meta name="robots" content="noindex, nofollow"/>
            </Head>
            
            {children}
        </>
    );
};

export default AuthEnologiaLayout;