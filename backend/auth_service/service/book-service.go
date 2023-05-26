package service

import (
	"fmt"
	"log"

	"github.com/marloxxx/golang_gin_gorm_GWT/dto"
	"github.com/marloxxx/golang_gin_gorm_GWT/entity"
	"github.com/marloxxx/golang_gin_gorm_GWT/repository"
	"github.com/mashingan/smapping"
)

type BookService interface {
	Insert(b dto.BookCreateDTO) entity.Book
	Update(b dto.BookUpdateDTO) entity.Book
	Delete(b entity.Book)
	All() []entity.Book
	FIndById(bookID uint64) entity.Book
	IsAllowedToEdit(userID string, bookID uint64) bool
}

// membuat interface book service dengan method insert, update, delete, all, find by id, is allowed to edit

type bookService struct {
	bookRepository repository.BookRepository
}

// membuat struct book service dengan field book repository

func NewBookService(bookRepo repository.BookRepository) BookService { // method dengan parameter book repository
	return &bookService{
		bookRepository: bookRepo,
	}
}

func (service *bookService) Insert(b dto.BookCreateDTO) entity.Book { // method insert untuk menambahkan data buku
	book := entity.Book{}                                     // membuat objek book
	err := smapping.FillStruct(&book, smapping.MapFields(&b)) // memanggil method fill struct pada smapping
	if err != nil {                                           // jika terjadi error
		log.Fatalf("Failed map %v:", err) // menampilkan error
	}
	res := service.bookRepository.InsertBook(book) // memanggil method insert book pada book repository
	return res
}

func (service *bookService) Update(b dto.BookUpdateDTO) entity.Book { // method update untuk mengubah data buku
	book := entity.Book{}                                     // membuat objek book
	err := smapping.FillStruct(&book, smapping.MapFields(&b)) // memanggil method fill struct pada smapping
	if err != nil {                                           // jika terjadi error
		log.Fatalf("Failed map %v:", err) // menampilkan error
	}
	res := service.bookRepository.UpdateBook(book) // memanggil method update book pada book repository
	return res                                     // mengembalikan nilai res
}

func (service *bookService) Delete(b entity.Book) { // method delete untuk menghapus data buku
	service.bookRepository.DeleteBook(b) // memanggil method delete book pada book repository
}

func (service *bookService) All() []entity.Book { // method all untuk menampilkan semua data buku
	return service.bookRepository.AllBook() // memanggil method all book pada book repository
}

func (service *bookService) FIndById(bookID uint64) entity.Book { // method find by id untuk menampilkan data buku berdasarkan id
	return service.bookRepository.FindBookID(bookID) // memanggil method find book id pada book repository
}

func (service *bookService) IsAllowedToEdit(userID string, bookID uint64) bool { // method is allowed to edit untuk mengecek apakah user yang login memiliki hak akses untuk mengubah data buku
	b := service.bookRepository.FindBookID(bookID) // memanggil method find book id pada book repository
	id := fmt.Sprintf("%v", b.UserID)              // mengubah data user id menjadi string
	return userID == id                            // mengembalikan nilai boolean
}
