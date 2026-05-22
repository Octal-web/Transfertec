export const PrivacyPolicy = ({ content }) => {
    return (
        <section className="pt-20 md:pt-30">
            <div className="container max-w-medium">
                <h1 className="text-5xl md:text-6xl text-eng-primary font-bold mb-10">{content.titulo}</h1>
                <div className="font-secondary text-eng-tertiary pb-24 [&_h1]:text-3xl [&_h1]:font-bold [&_h1]:text-eng-secondary [&_h2]:text-xl [&_h2]:text-eng-primary [&_h1]:mb-2 [&_li]:list-disc [&_li]:list-inside" dangerouslySetInnerHTML={{ __html: content.texto }} /> 
            </div>
        </section>
    );
};