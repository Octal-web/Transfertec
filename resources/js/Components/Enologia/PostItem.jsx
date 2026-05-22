import { Reveal } from './Reveal';

export const PostItem = ({ post }) => {
    return (
        <section className="relative pt-10 xl:pt-20 pb-10 md:pb-24">
            <div className="container max-w-large">
                <div className="flex items-center gap-3 mb-16">
                    <span className="py-2 px-4 bg-eno-primary font-secondary text-xs text-white font-bold rounded-xl mr-2">{post.categoria}</span>
                    <CalendarIcon />
                    <span className="text-neutral-600 font-light">{post.data}</span>
                </div>

                <h2 className="text-3xl md:text-4xl 2xl:text-5xl text-eno-tertiary mb-6 mx-10 xl:mb-10">{post.titulo}</h2>

                <div className="font-secondary font-light" dangerouslySetInnerHTML={{ __html: post.conteudo }} />
            </div>
        </section>
    );
};

export const CalendarIcon = () => {
    return (
        <svg 
            width="25"
            height="25"
            viewBox="0 0 24.881 25.652"
            className="fill-eno-primary"
            xmlns="http://www.w3.org/2000/svg"
        >
            <g>
                <path 
                    d="M24.868,15.8c.017-3.46.035-7.038-.085-10.544V5.246a3.348,3.348,0,0,0-1.056-1.754,2.88,2.88,0,0,0-1.8-.91H19.735l-.027-.027V.764a1.772,1.772,0,0,0-.326-.535.977.977,0,0,0-1.434.224,1.779,1.779,0,0,0-.162.368V2.556l-.026.027H7.128L7.1,2.556V.764A.6.6,0,0,0,6.891.34,1.222,1.222,0,0,0,6.111,0a1.062,1.062,0,0,0-.93.862,4.613,4.613,0,0,0-.006.917,5.48,5.48,0,0,1,.009.772l-.031.031H2.96A3.668,3.668,0,0,0,.1,5.372c-.144,6.433-.14,12.169.012,17.535l0,.019a3.527,3.527,0,0,0,3.058,2.726H21.652a3.533,3.533,0,0,0,3.217-3.226c-.023-2.206-.012-4.453,0-6.626M5.289,6.318a.906.906,0,0,0,.768.534.946.946,0,0,0,.882-.389A1.777,1.777,0,0,0,7.1,6.093V4.447H17.787V6.036a1.7,1.7,0,0,0,.288.559.992.992,0,0,0,1.309.03,1.291,1.291,0,0,0,.325-.532V4.447h1.876c.445,0,1.36.769,1.36,1.245V7.625h-21V5.749a1.812,1.812,0,0,1,1.36-1.3H5.18V5.921a3.419,3.419,0,0,0,.109.4M22.945,9.547V22.54a1.113,1.113,0,0,1-.4.7,1.752,1.752,0,0,1-1.073.542l-17.879,0a1.655,1.655,0,0,1-1.44-.828c-.033-.061-.185-.417-.207-.488V9.547Z" 
                />
                <path 
                    d="M86.692,117.08H84.056l-.145.145V119h2.781Z" 
                    transform="translate(-75.407 -105.215)" 
                />
                <path 
                    d="M137.018,186.294v-1.922h-2.781v1.776l.145.145Z" 
                    transform="translate(-120.633 -165.687)" 
                />
                <path 
                    d="M137.018,117.226l-.145-.145h-2.491l-.145.145V119h2.781Z" 
                    transform="translate(-120.633 -105.215)" 
                />
                <path 
                    d="M187.345,117.226l-.145-.145h-2.491l-.145.145V119h2.781Z" 
                    transform="translate(-165.86 -105.215)"
                />
                <path 
                    d="M84.212,186.294h2.48v-1.922H83.91v1.766l.017.026a.377.377,0,0,0,.284.131" 
                    transform="translate(-75.406 -165.687)"
                />
                <path 
                    d="M187.2,186.294l.145-.145v-1.776h-2.782v1.776l.145.145Z" 
                    transform="translate(-165.859 -165.687)"
                />
                <rect width="2.724" height="1.922" transform="translate(3.461 18.685)" />
                <rect width="2.724" height="1.922" transform="translate(3.461 11.865)" />
                <rect width="2.781" height="1.865" transform="translate(8.504 15.304)" />
                <rect width="2.781" height="1.865" transform="translate(13.604 15.304)" />
                <rect width="2.781" height="1.865" transform="translate(18.704 15.304)" />
                <path 
                    d="M33.833,152.874h2.641v-1.865H33.75v1.579a.131.131,0,0,0-.045.105l0,.033Z" 
                    transform="translate(-30.289 -135.705)"
                />
            </g>
        </svg>
    );
};