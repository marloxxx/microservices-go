package controller

import (
	"fmt"
	"net/http"
	"strconv"

	"github.com/dgrijalva/jwt-go"
	"github.com/gin-gonic/gin"
	"github.com/marloxxx/golang_gin_gorm_GWT/dto"
	"github.com/marloxxx/golang_gin_gorm_GWT/entity"
	"github.com/marloxxx/golang_gin_gorm_GWT/helper"
	"github.com/marloxxx/golang_gin_gorm_GWT/service"
)

// buat interface untuk controller book
type BookController interface {
	All(context *gin.Context)
	FindByID(context *gin.Context)
	Insert(context *gin.Context)
	Update(context *gin.Context)
	Delete(context *gin.Context)
}

type bookController struct {
	bookService service.BookService // use book service
	jwtService  service.JWTService  // use auth from JWT
}

func NewBookController(bookServ service.BookService, jwtServ service.JWTService) BookController { // method dengan parameter auth JWT
	return &bookController{
		bookService: bookServ,
		jwtService:  jwtServ,
	}
}

func (c *bookController) All(context *gin.Context) { // method all untuk menampilkan semua data buku
	var books []entity.Book = c.bookService.All()  // memanggil method all pada service book
	res := helper.BuildResponse(true, "OK", books) // memanggil method build response pada helper
	context.JSON(http.StatusOK, res)               // menampilkan data buku
} // method all untuk menampilkan semua data buku

func (c *bookController) FindByID(context *gin.Context) { // method find by id untuk menampilkan data buku berdasarkan id
	id, err := strconv.ParseUint(context.Param("id"), 0, 0) // menampilkan data buku berdasarkan id
	if err != nil {                                         // jika error maka tampilkan pesan error
		res := helper.BuildErrorResponse("No param id was found", err.Error(), helper.EmptyObj{}) // memanggil method build error response pada helper dengan parameter pesan error dan objek kosong
		context.AbortWithStatusJSON(http.StatusBadRequest, res)                                   // menampilkan pesan error
	}
	var book entity.Book = c.bookService.FIndById(id) // memanggil method find by id pada service book
	if (book == entity.Book{}) {                      // jika data buku kosong maka tampilkan pesan error
		res := helper.BuildErrorResponse("Data not found", "No Data with given id", helper.EmptyObj{}) // memanggil method build error response pada helper dengan parameter pesan error dan objek kosong
		context.JSON(http.StatusNotFound, res)                                                         // menampilkan pesan error
	} else {
		res := helper.BuildResponse(true, "OK", book) // memanggil method build response pada helper
		context.JSON(http.StatusOK, res)              // menampilkan data buku
	}
}

func (c *bookController) Insert(context *gin.Context) { // method insert untuk menambahkan data buku
	var bookCreateDTO dto.BookCreateDTO          // membuat objek book create dto
	errDTO := context.ShouldBind(&bookCreateDTO) // memanggil method should bind pada context untuk memasukkan data ke dalam objek book create dto
	if errDTO != nil {                           // jika error maka tampilkan pesan error
		res := helper.BuildErrorResponse("Failed to process request", errDTO.Error(), helper.EmptyObj{}) // memanggil method build error response pada helper dengan parameter pesan error dan objek kosong
		context.JSON(http.StatusBadRequest, res)                                                         // menampilkan pesan error
	} else { // jika tidak maka tampilkan data buku
		authHeader := context.GetHeader("Authorization")          // memanggil method get header pada context untuk mengambil data dari header
		userID := c.getUserIDByToken(authHeader)                  // memanggil method get user id by token untuk mengambil id user berdasarkan token
		convertedUserID, err := strconv.ParseUint(userID, 10, 64) // mengkonversi id user dari string ke uint64
		if err == nil {                                           // jika tidak error maka masukkan id user ke dalam objek book create dto
			bookCreateDTO.UserID = convertedUserID
		}
		result := c.bookService.Insert(bookCreateDTO)        // memanggil method insert pada service book
		response := helper.BuildResponse(true, "OK", result) // memanggil method build response pada helper
		context.JSON(http.StatusOK, response)                // menampilkan data buku
	}
}

func (c *bookController) Update(context *gin.Context) { // method update untuk mengedit data buku
	var bookUpdateDTO dto.BookUpdateDTO          // membuat objek book update dto
	errDTO := context.ShouldBind(&bookUpdateDTO) // memanggil method should bind pada context untuk memasukkan data ke dalam objek book update dto
	if errDTO != nil {                           // jika error maka tampilkan pesan error
		res := helper.BuildErrorResponse("Failed to process request", errDTO.Error(), helper.EmptyObj{}) // memanggil method build error response pada helper dengan parameter pesan error dan objek kosong
		context.JSON(http.StatusBadRequest, res)                                                         // menampilkan pesan error
	}
	authHeader := context.GetHeader("Authorization")          // memanggil method get header pada context untuk mengambil data dari header
	token, errToken := c.jwtService.ValidateToken(authHeader) // memanggil method validate token pada service jwt untuk memvalidasi token
	if errToken != nil {                                      // jika error maka tampilkan pesan error
		panic((errToken.Error())) // menampilkan pesan error
	}
	claims := token.Claims.(jwt.MapClaims)                       // memanggil method claims pada token untuk mengambil data dari token
	userID := fmt.Sprintf("%v", claims["user_id"])               // mengambil data user id dari token
	if c.bookService.IsAllowedToEdit(userID, bookUpdateDTO.ID) { // memanggil method is allowed to edit pada service book untuk memvalidasi apakah user yang login memiliki akses untuk mengedit data buku
		id, errID := strconv.ParseUint(userID, 10, 64) // mengkonversi id user dari string ke uint64
		if errID == nil {                              // jika tidak error maka masukkan id user ke dalam objek book update dto
			bookUpdateDTO.UserID = id
		}
		result := c.bookService.Update(bookUpdateDTO)        // memanggil method update pada service book
		response := helper.BuildResponse(true, "OK", result) // memanggil method build response pada helper
		context.JSON(http.StatusOK, response)                // menampilkan data buku
	} else { // jika user tidak memiliki akses maka tampilkan pesan error
		response := helper.BuildErrorResponse("You dont have permission", "You are not the owner", helper.EmptyObj{}) // memanggil method build error response pada helper dengan parameter pesan error dan objek kosong
		context.JSON(http.StatusForbidden, response)                                                                  // menampilkan pesan error
	}

}

func (c *bookController) Delete(context *gin.Context) { // method delete untuk menghapus data buku
	var book entity.Book                                    // membuat objek book
	id, err := strconv.ParseUint(context.Param("id"), 0, 0) // mengkonversi id dari string ke uint64
	if err != nil {                                         // jika error maka tampilkan pesan error
		response := helper.BuildErrorResponse("Failed to get id", "No param id were found", helper.EmptyObj{}) // memanggil method build error response pada helper dengan parameter pesan error dan objek kosong
		context.JSON(http.StatusBadRequest, response)                                                          // menampilkan pesan error
	}
	book.ID = id                                              // memasukkan id ke dalam objek book
	authHeader := context.GetHeader("Authorization")          // memanggil method get header pada context untuk mengambil data dari header
	token, errToken := c.jwtService.ValidateToken(authHeader) // memanggil method validate token pada service jwt untuk memvalidasi token
	if errToken != nil {                                      // jika error maka tampilkan pesan error
		panic((errToken.Error())) // menampilkan pesan error
	}
	claims := token.Claims.(jwt.MapClaims)              // memanggil method claims pada token untuk mengambil data dari token
	userID := fmt.Sprintf("%v", claims["user_id"])      // mengambil data user id dari token
	if c.bookService.IsAllowedToEdit(userID, book.ID) { // memanggil method is allowed to edit pada service book untuk memvalidasi apakah user yang login memiliki akses untuk menghapus data buku
		c.bookService.Delete(book)                                     // memanggil method delete pada service book
		res := helper.BuildResponse(true, "Delete", helper.EmptyObj{}) // memanggil method build response pada helper dengan parameter pesan berhasil dan objek kosong
		context.JSON(http.StatusOK, res)                               // menampilkan pesan berhasil
	} else { // jika user tidak memiliki akses maka tampilkan pesan error
		response := helper.BuildErrorResponse("You dont have permission", "You are not the owner", helper.EmptyObj{}) // memanggil method build error response pada helper dengan parameter pesan error dan objek kosong
		context.JSON(http.StatusForbidden, response)                                                                  // menampilkan pesan error
	}
}

func (c *bookController) getUserIDByToken(token string) string { // method get user id by token untuk mengambil id user berdasarkan token
	aToken, err := c.jwtService.ValidateToken(token) // memanggil method validate token pada service jwt untuk memvalidasi token
	if err != nil {                                  // jika error maka tampilkan pesan error
		panic(err.Error()) // menampilkan pesan error
	}
	claims := aToken.Claims.(jwt.MapClaims)    // memanggil method claims pada token untuk mengambil data dari token
	id := fmt.Sprintf("%v", claims["user_id"]) // mengambil data user id dari token
	return id                                  // mengembalikan id user
}
