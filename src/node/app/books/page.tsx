"use client";  // クライアントコンポーネントであることを明示

import axios from "axios";
import { useEffect, useState } from "react";

// 型を作成
type Book = {
  id: number;
  title: string;
  author: string;
};

const BookPage = () => {
  // 本のリストを管理するstate
  const [books, setBooks] = useState<Book[]>([]);

  // 新規作成の入力フォームのstate
  const [title, setTitle] = useState<string>("");
  const [author, setAuthor] = useState<string>("");

  // 編集フォームのstate
  const [editBookId, setEditBookId] = useState<number | null>(null);
  const [editTitle, setEditTitle] = useState<string>("");
  const [editAuthor, setEditAuthor] = useState<string>("");

  // エラーメッセージのstate
  const [errorMessage, setErrorMessage] = useState<string>("");

  // 本の一覧取得(Read処理)
  useEffect(() => {
    axios.get("/api/books/")
      .then((response) => setBooks(response.data))
      .catch(() => setErrorMessage("本のデータを取得できませんでした。"));
  }, []);

  // 本の新規追加(Creat処理)
  const createNewBook = () => {
    axios.post("/api/books/", {title, author})
      .then((response) => {
      setBooks([...books, response.data]);  // 本を追加

      // 各値をリセット
      setTitle("");
      setAuthor("");
      setErrorMessage("")
    })
      .catch((error) => {
      console.error(error);
      setErrorMessage("本の追加に失敗しました。");
    });
  };

  // 本の削除(Delete処理)
  const deleteBook = (id: number) => {
    axios.delete(`/api/books/${id}`)
      .then(() => setBooks(books.filter((book) => book.id !== id)))
      .catch(() => setErrorMessage("本の削除に失敗しました。"));
  };

  const startEdit = (book: Book) => {
    setEditBookId(book.id);
    setEditTitle(book.title);
    setEditAuthor(book.author);
  };

  const cancelEdit = () => {
    setEditBookId(null);
    setEditTitle("");
    setEditAuthor("");
  };

  // 本の更新(Update処理)
  const updateBook = (id: number) => {
    axios.patch(`/api/books/${id}`, { title: editTitle, author: editAuthor})
      .then((response) => {
        setBooks((prevBooks) => 
          prevBooks.map((book) => 
            book.id === id ? { ...book, title: response.data.title, author: response.data.author } : book
          )
        );
        cancelEdit();
      })
      .catch(() => setErrorMessage("本の更新に失敗しました。"));
  };

  return (
    <div>
      <h1><b>本の管理 (CRUDテスト)</b></h1>

      {/* エラーメッセージを表示 */}
      {errorMessage && <p style={{ color: "red" }}>{errorMessage}</p>}

      {/* テーブル内の本データの一覧表示 */}
      <ul>
        {books.map((book) => (
          <li key={book.id}>
            {editBookId === book.id ? (
              <>
                タイトル: <input value={editTitle} onChange={(e) => setEditTitle(e.target.value)} />
                作者: <input value={editAuthor} onChange={(e) => setEditAuthor(e.target.value)} />
                <button onClick={() => updateBook(book.id)}>[更新]</button>
                <button onClick={cancelEdit}>[キャンセル]</button>
              </>
            ) : (
              <>
                ID: {book.id} - タイトル: {book.title} - 作者: {book.author}
                <button onClick={() => startEdit(book)}>[編集]</button>
                <button onClick={() => deleteBook(book.id)}>[削除]</button>
              </>
            )}
          </li>
        ))}
      </ul>

      {/* 本の新規登録 */}
      <div>
        <label>
          タイトル:
          <input type="text" value={title} onChange={(e) => setTitle(e.target.value)}/>
        </label>
        <label>
          作者:
          <input type="text" value={author} onChange={(e) => setAuthor(e.target.value)}/>
        </label>
        <button onClick={createNewBook}>[作成]</button>
      </div>
    </div>
  );
};

// BookPageコンポーネントをエクスポート
export default BookPage;
