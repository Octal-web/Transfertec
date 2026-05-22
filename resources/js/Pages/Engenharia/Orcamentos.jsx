import DefaultLayout from '@/Layouts/EngenhariaLayout';

import { BudgetBanner } from '@/Components/Engenharia/BudgetBanner';
import { BudgetForm } from '@/Components/Engenharia/BudgetForm';

const Page = () => {
    return (
        <DefaultLayout>
            <BudgetBanner />
            <BudgetForm />
        </DefaultLayout>
    );
};

export default Page;
