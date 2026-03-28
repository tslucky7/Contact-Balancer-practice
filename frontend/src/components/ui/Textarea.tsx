interface TextareaFieldProps {
  label: string;
  name: string;
  value?: string;
  onChange: (e: React.ChangeEvent<HTMLTextAreaElement>) => void;
  placeholder: string;
  required: boolean;
  autoComplete: string;
}

export const TextareaField = ({
  label,
  name,
  value = '',
  onChange,
  placeholder,
  required,
  autoComplete,
}: TextareaFieldProps) => {
  return (
    <>
      <label htmlFor={name}>{label}</label>
      <textarea
        id={name}
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
