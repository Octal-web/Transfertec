export const Label = ({ title, idioma }) => {
    return (
        <div className="flex items-center mb-2">
            <img
                src={`/eno/admin/img/flags/${idioma}.png`}
                className="w-5 mr-1"
                alt={`${idioma} flag`}
            />
            <label className="block font-bold text-gray-500">{title}</label>
        </div>
    );
};
