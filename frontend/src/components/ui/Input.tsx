interface InputFieldProps {
  label: string;
  name: string;
  type: string;
  value?: string;
  onChange: (e: React.ChangeEvent<HTMLInputElement>) => void;
  placeholder: string;
  required: boolean;
  autoComplete: string;
}

export const InputField = ({
  label,
  name,
  type,
  value = '',
  onChange,
  placeholder,
  required,
  autoComplete,
}: InputFieldProps) => {
  return (
    <>
      <label htmlFor={name}>{label}</label>
      <input
        id={name}
        type={type}
        name={name}
        value={value}
        onChange={onChange}
        placeholder={placeholder}
        required={required}
        autoComplete={autoComplete}
        className="w-full rounded-sm border border-slate-300 bg-white p-2 text-slate-900 placeholder:text-slate-500 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-400"
      />
    </>
  );
};
