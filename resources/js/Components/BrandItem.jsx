import React, { useRef, useEffect } from 'react';
import { Link } from '@inertiajs/react';

import { Reveal } from './Reveal';

export const BrandItem = ({ brand }) => {
    const videoRef = useRef(null);

    const isTouchDevice = () => {
        return window.matchMedia('(pointer: coarse)').matches;
    };

    const handleMouseEnter = () => {
        if (videoRef.current) {
            videoRef.current.play();
        }
    };

    const handleMouseLeave = () => {
        if (videoRef.current && !isTouchDevice()) {
            videoRef.current.pause();
        }
    };

    useEffect(() => {
        if (isTouchDevice() && videoRef.current) {
            videoRef.current.play();
        }
    }, []);

    return (
        <div
            className="relative overflow-hidden group"
            onMouseEnter={handleMouseEnter}
            onMouseLeave={handleMouseLeave}
        >
            <video
                ref={videoRef}
                src={brand.video}
                playsInline
                loop
                muted
                className="absolute w-[110%] h-[110%] left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 object-cover blur grayscale-[40%] transition-all duration-300 group-hover:blur-[0px]"
            />

            <div className={`absolute inset-0 opacity-70 ${brand.color.bg} mix-blend-overlay`} />
            <div className="absolute inset-0 bg-gradient-to-t opacity-90 from-black to-transparent" />
            <div className="absolute left-0 right-0 top-0 h-80 opacity-50 bg-gradient-to-t from-transparent to-black" />

            <Reveal className="relative w-[90%] max-w-96 mx-auto h-full flex flex-col items-center justify-end max-md:py-[22%] pb-[18%] 2xl:pb-[22%]" direction="top">
                <Link href={brand.link} className="max-md:px-10 max-2xl:px-6 transition-all hover:opacity-80">
                    <img src={brand.logo} />
                </Link>

                <Link href={brand.link} className="font-secondary text-xl text-white text-center uppercase mx-4 my-[10%] transition-all hover:opacity-60">
                    {brand.description}
                </Link>

                <ul className="flex items-center gap-4 mb-[10%] 2xl:mb-[40%]">
                    {brand.social.map((media, index) => (
                        <li key={index}>
                            <a href={media.link} className="transition-all hover:opacity-80" target="_blank" rel="noopener noreferrer">
                                <img src={`/site/img/${media.name}-icon.png`} alt="Phone icon" className="block" />
                            </a>
                        </li>
                    ))}
                </ul>
            </Reveal>
        </div>
    );
};
