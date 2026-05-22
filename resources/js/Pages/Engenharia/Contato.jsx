import DefaultLayout from '@/Layouts/EngenhariaLayout';

import { ContactBanner } from '@/Components/Engenharia/ContactBanner';
import { ContactForm } from '@/Components/Engenharia/ContactForm';
import { ContactMap } from '@/Components/Engenharia/ContactMap';

const Page = () => {
    return (
        <DefaultLayout>
            <ContactBanner />

            <section className="pt-20 pb-24">
                <div className="container max-w-large">
                    <div className="grid md:grid-cols-2 gap-16">
                        <ContactForm />
                        <ContactMap />
                    </div>
                </div>
            </section>
        </DefaultLayout>
    );
};

export default Page;
