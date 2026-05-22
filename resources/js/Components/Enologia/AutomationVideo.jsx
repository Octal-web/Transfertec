import React, { useEffect, useRef, useState } from 'react';

export const AutomationVideo = ({ url, title = 'YouTube Video' }) => {
    const [isInteracting, setIsInteracting] = useState(false);
    
    if (!url) return null;
    
    const getYouTubeId = (url) => {
        const regex = /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]+)/;
        const match = url.match(regex);
        return match ? match[1] : null;
    };
    
    const videoId = getYouTubeId(url);
    if (!videoId) return <div>URL inválida</div>;
    
    return (
        <section className="pt-20 pb-10">
            <div className="container max-w-medium">
                <div className="w-full aspect-video relative">
                    <iframe
                        className="w-full h-full rounded-md"
                        src={`https://www.youtube.com/embed/${videoId}`}
                        title={title}
                        frameBorder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowFullScreen
                    />
                    
                    {!isInteracting && (
                        <div 
                            className="absolute inset-0 bg-transparent cursor-pointer z-10"
                            onMouseDown={() => setIsInteracting(true)}
                            onTouchStart={() => setIsInteracting(true)}
                        />
                    )}
                    
                    <span className="hidden">{isInteracting && setTimeout(() => setIsInteracting(false), 5000)}</span>
                </div>
            </div>
        </section>
    );
};