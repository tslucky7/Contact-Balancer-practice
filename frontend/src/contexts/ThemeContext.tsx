import { createContext, useEffect, useState } from "react";

export type Theme = 'light' | 'dark' | 'system';
export type ThemeContextType = {
  theme: Theme;
  setTheme: (theme: Theme) => void;
};
const STORAGE_KEY = 'theme';
const darkQuery = window.matchMedia('(prefers-color-scheme: dark)');
const savedTheme = localStorage.getItem(STORAGE_KEY) as Theme;

export const ThemeContext = createContext<ThemeContextType>({
  theme: savedTheme,
  setTheme: () => {},
});

/**
 * 現在適用すべき実際のモード（dark or light）を判定する
 */
const getResolvedMode = (theme: Theme): 'dark' | 'light' => {
  if (theme !== 'system') return theme;
  return darkQuery.matches ? 'dark' : 'light';
};

/**
 * DOM（htmlタグ）にクラスを反映し、LocalStorageに保存する
 */
const updateDOM = (theme: Theme) => {
  const mode = getResolvedMode(theme);
  document.documentElement.classList.toggle('dark', mode === 'dark');
  localStorage.setItem(STORAGE_KEY, theme);
};

// 4. OS設定の変更監視（リスナーの登録）

export const ThemeProvider = ({ children }: { children: React.ReactNode }) => {
  const [theme, setTheme] = useState<Theme>(() => {
    return savedTheme;
  });
  const listener = () => {
    if (theme === 'system') updateDOM(theme);
  };
  useEffect(() => {
    updateDOM(theme);
    darkQuery.addEventListener('change', listener);
    return () => {
      darkQuery.removeEventListener('change', listener);
    };
  }, [theme]);

  return (
    <ThemeContext.Provider value={{ theme, setTheme: setTheme }}>
      {children}
    </ThemeContext.Provider>
  );
}
