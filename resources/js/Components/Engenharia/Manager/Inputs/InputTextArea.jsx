import React from 'react';

export const InputTextArea = ({ title, name, value, idioma, onChange, max }) => {
    const handleChange = (e) => {
        const { name, value } = e.target;
        onChange(name, value);
    };

    return (
        <div className="mb-6">
            <div className="flex items-center mb-2">
                <img src={`/eng/admin/img/flags/${idioma}.png`} className="w-5 mr-1" />
                <label className="block font-bold text-gray-500">{title}</label>
            </div>
            <textarea
                type="text"
                name={name}
                value={value}
                className="w-full rounded-lg border-gray-300 bg-transparent p-3 font-normal text-sm text-black outline-none transition focus:border-eng-primary focus:ring-0 active:border-eng-primary disabled:cursor-default disabled:bg-whiter"
                onChange={handleChange}
                {...(max ? { maxLength: max } : {})}
                rows="5"
            />
        </div>
    );
};