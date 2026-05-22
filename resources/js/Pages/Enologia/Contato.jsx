import DefaultLayout from '@/Layouts/EnologiaLayout';

import { ContactBanner } from '@/Components/Enologia/ContactBanner';
import { ContactForm } from '@/Components/Enologia/ContactForm';
import { ContactMap } from '@/Components/Enologia/ContactMap';

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
