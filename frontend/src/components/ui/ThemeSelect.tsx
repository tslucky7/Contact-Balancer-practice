import { useTheme } from '../../features/inquiry/hooks/useTheme';

export const ThemeSelect = () => {
  const { theme, setTheme } = useTheme();

  return (
    <div className="mb-4 flex items-center justify-end">
      <label
        htmlFor="theme-select"
        className="mr-2 text-sm text-slate-700 dark:text-slate-300"
      >
        テーマ
      </label>
      <select
        id="theme-select"
        className="rounded-md border border-slate-300 bg-white px-2 py-1 text-sm text-slate-900 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
        value={theme}
        onChange={(e) => setTheme(e.target.value as typeof theme)}
      >
        <option value="system">システム</option>
        <option value="light">ライト</option>
        <option value="dark">ダーク</option>
      </select>
    </div>
  );
};
