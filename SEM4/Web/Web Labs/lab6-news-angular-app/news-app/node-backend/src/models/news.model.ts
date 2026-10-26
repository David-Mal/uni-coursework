export interface NewsArticle {
  id?: number;
  title: string;
  text: string;
  producer?: string;
  date: string;
  category: string;
}

export interface NewsFilter {
  category?: string;
  start?: string;
  end?: string;
}
