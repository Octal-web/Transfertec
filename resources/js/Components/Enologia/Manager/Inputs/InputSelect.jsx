import Select from "react-select";
import { Label } from "./Label";

export const InputSelect = ({
    title,
    name,
    value,
    idioma,
    onChange,
    options,
    isMulti = false,
}) => {
    const flattenOptions = (groupedOptions) =>
        groupedOptions.reduce((acc, group) => {
            if (group.options) {
                return acc.concat(group.options);
            }
            return acc.concat(group);
        }, []);

    const allOptions = flattenOptions(options);

    const handleChange = (selectedOption) => {
        if (isMulti) {
            const values = selectedOption
                ? selectedOption.map((opt) => opt.value)
                : [];
            onChange(name, values);
        } else {
            onChange(name, selectedOption ? selectedOption.value : null);
        }
    };

    const selectedOption = isMulti
        ? allOptions.filter((option) => value?.includes(option.value))
        : allOptions.find((option) => option.value === value);

    return (
        <div className="mb-6 [&_.admin-select\:__control]:p-3">
            <Label title={title} idioma={idioma} />

            <Select
                options={options}
                value={selectedOption}
                onChange={handleChange}
                placeholder="Selecione uma opção..."
                isSearchable={allOptions.length > 10}
                isMulti={isMulti}
                classNamePrefix="admin-eno-select"
            />
        </div>
    );
};
