interface ConfirmFieldProps {
  label: string;
  value: string;
}

export const ConfirmField = ({ label, value }: ConfirmFieldProps) => {
  return (
    <div className="border-b border-slate-200 pb-2 dark:border-slate-700">
      <dt className="text-sm font-bold text-slate-500 dark:text-slate-400">
        {label}
      </dt>
      <dd className="mt-1 text-base text-slate-900 dark:text-slate-100 whitespace-pre-wrap">
        {value || '(未入力)'}
      </dd>
    </div>
  );
};
