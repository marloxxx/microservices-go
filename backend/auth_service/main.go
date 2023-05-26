package main

import (
	"github.com/gin-gonic/gin"
	"github.com/marloxxx/microservices-go/backend/auth_service/config"
	"github.com/marloxxx/microservices-go/backend/auth_service/controller"
	"github.com/marloxxx/microservices-go/backend/auth_service/repository"
	"github.com/marloxxx/microservices-go/backend/auth_service/service"
	"gorm.io/gorm"
)

var (
	db             *gorm.DB                  = config.SetupDatabaseConnection()
	userRepository repository.UserRepository = repository.NewUserRepository(db)
	jwtService     service.JWTService        = service.NewJWTService()
	userService    service.UserService       = service.NewUserService(userRepository)
	authService    service.AuthService       = service.NewAuthService(userRepository)
	authController controller.AuthController = controller.NewAuthController(authService, jwtService)
)

// membuat variabel db, userRepository, bookRepository, jwtService, userService, bookService, authService, authController, userController, bookController

func main() {
	defer config.CloseDatabaseConnection(db) // memanggil method close database connection pada config
	r := gin.Default()                       // membuat objek r dengan nilai default

	authRoutes := r.Group("api/auth") // membuat group auth
	{
		authRoutes.POST("/login", authController.Login)       // membuat route login dengan method post
		authRoutes.POST("/register", authController.Register) // membuat route register dengan method post
	}
	r.Run() // menjalankan server
}
