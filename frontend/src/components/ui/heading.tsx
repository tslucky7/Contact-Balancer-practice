interface HeadingProps {
  children: React.ReactNode;
  className?: string;
}

export const Heading = ({ children, className }: HeadingProps) => {
  return (
    <h1 className={`heading text-3xl font-bold text-slate-900 dark:text-slate-100 ${className}`}>
      {children}
    </h1>
  );
};
