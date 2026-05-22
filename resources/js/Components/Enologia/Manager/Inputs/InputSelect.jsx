import React from 'react';
import Select from 'react-select';

export const InputSelect = ({ title, name, value, idioma, onChange, options }) => {
    const handleChange = (selectedOption) => {
        onChange(name, selectedOption ? selectedOption.value : null);
    };

    const selectedOption = options.find((option) => option.value === value);

    return (
        <div className="mb-6 [&_.admin-select\:__control]:p-3">
            <div className="flex items-center mb-2">
                <img src={`/eng/admin/img/flags/${idioma}.png`} className="w-5 mr-1" alt={`${idioma} flag`} />
                <label className="block font-bold text-gray-500">{title}</label>
            </div>
            <Select
                options={options}
                value={selectedOption}
                onChange={handleChange}
                placeholder="Selecione uma opção..."
                isSearchable={false}
                classNamePrefix="admin-eno-select"
            />
        </div>
    );
};