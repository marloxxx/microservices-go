package main

import (
	"github.com/gin-gonic/gin"
	"github.com/marloxxx/golang_gin_gorm_GWT/config"
	"github.com/marloxxx/golang_gin_gorm_GWT/controller"
	"github.com/marloxxx/golang_gin_gorm_GWT/middleware"
	"github.com/marloxxx/golang_gin_gorm_GWT/repository"
	"github.com/marloxxx/golang_gin_gorm_GWT/service"
	"gorm.io/gorm"
)

var (
	db             *gorm.DB                  = config.SetupDatabaseConnection()
	userRepository repository.UserRepository = repository.NewUserRepository(db)
	bookRepository repository.BookRepository = repository.NewBookRepository(db)
	jwtService     service.JWTService        = service.NewJWTService()
	userService    service.UserService       = service.NewUserService(userRepository)
	bookService    service.BookService       = service.NewBookService(bookRepository)
	authService    service.AuthService       = service.NewAuthService(userRepository)
	authController controller.AuthController = controller.NewAuthController(authService, jwtService)
	userController controller.UserController = controller.NewUserController(userService, jwtService)
	bookController controller.BookController = controller.NewBookController(bookService, jwtService)
) 
// membuat variabel db, userRepository, bookRepository, jwtService, userService, bookService, authService, authController, userController, bookController

func main() {
	defer config.CloseDatabaseConnection(db) // memanggil method close database connection pada config
	r := gin.Default() // membuat objek r dengan nilai default

	authRoutes := r.Group("api/auth") // membuat group auth
	{
		authRoutes.POST("/login", authController.Login) // membuat route login dengan method post
		authRoutes.POST("/register", authController.Register) // membuat route register dengan method post
	}

	userRoutes := r.Group("api/user", middleware.AuthorizeJWT(jwtService)) // membuat group user
	{
		userRoutes.GET("/profile", userController.Profile) // membuat route profile dengan method get
		userRoutes.PUT("/profile", userController.Update) // membuat route profile dengan method put
	}

	bookRoutes := r.Group("api/books", middleware.AuthorizeJWT(jwtService)) // membuat group books
	{
		bookRoutes.GET("/", bookController.All) // membuat route books dengan method get
		bookRoutes.POST("/", bookController.Insert) // membuat route books dengan method post
		bookRoutes.GET("/:id", bookController.FindByID) // membuat route books dengan method get
		bookRoutes.PUT("/:id", bookController.Update) // membuat route books dengan method put
		bookRoutes.DELETE("/:id", bookController.Delete) // membuat route books dengan method delete
	}

	r.Run() // menjalankan server
}
