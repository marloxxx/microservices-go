package repository

import (
	"github.com/marloxxx/golang_gin_gorm_GWT/entity"
	"gorm.io/gorm"
)

type BookRepository interface {
	InsertBook(b entity.Book) entity.Book
	UpdateBook(b entity.Book) entity.Book
	DeleteBook(b entity.Book)
	AllBook() []entity.Book
	FindBookID(bookID uint64) entity.Book
}

// membuat interface book repository dengan method insert, update, delete, all, find by id

type bookConnection struct {
	connection *gorm.DB
}

// membuat struct book connection dengan field connection

func NewBookRepository(dbConn *gorm.DB) BookRepository { // method dengan parameter db connection
	return &bookConnection{
		connection: dbConn,
	}
}

func (db *bookConnection) InsertBook(b entity.Book) entity.Book { // method insert untuk menambahkan data buku
	db.connection.Save(&b)                 // memanggil method save pada db connection
	db.connection.Preload("User").Find(&b) // memanggil method find pada db connection
	return b
}

func (db *bookConnection) UpdateBook(b entity.Book) entity.Book { // method update untuk mengubah data buku
	db.connection.Save(&b)                 // memanggil method save pada db connection
	db.connection.Preload("User").Find(&b) // memanggil method find pada db connection
	return b
}

func (db *bookConnection) DeleteBook(b entity.Book) { // method delete untuk menghapus data buku
	db.connection.Delete(&b) // memanggil method delete pada db connection
}

func (db *bookConnection) FindBookID(bookID uint64) entity.Book { // method find by id untuk menampilkan data buku berdasarkan id
	var book entity.Book                              // membuat objek book
	db.connection.Preload("User").Find(&book, bookID) // memanggil method find pada db connection
	return book                                       // mengembalikan nilai book
}

func (db *bookConnection) AllBook() []entity.Book { // method all untuk menampilkan semua data buku
	var books []entity.Book                    // membuat objek slice book
	db.connection.Preload("User").Find(&books) // memanggil method find pada db connection
	return books
}
