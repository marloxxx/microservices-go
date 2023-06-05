package main

import (
	"github.com/gin-gonic/gin"
	"github.com/marloxxx/microservices-go/backend/category_service/config"
	"github.com/marloxxx/microservices-go/backend/category_service/controller"
	"github.com/marloxxx/microservices-go/backend/category_service/repository"
	"github.com/marloxxx/microservices-go/backend/category_service/service"
	"gorm.io/gorm"
)

var (
	db                 *gorm.DB                      = config.SetupDatabaseConnection()
	categoryRepository repository.CategoryRepository = repository.NewCategoryRepository(db)
	categoryService    service.CategoryService       = service.NewCategoryService(categoryRepository)
	categoryController controller.CategoryController = controller.NewCategoryController(categoryService)
)

// membuat variable db dengan nilai setup database connection
func main() {
	defer config.CloseDatabaseConnection(db)
	r := gin.Default()

	categoryRoutes := r.Group("/api/categories")
	{
		categoryRoutes.GET("/", categoryController.All)
		categoryRoutes.POST("/", categoryController.Insert)
		categoryRoutes.GET("/:id", categoryController.FindByID)
		categoryRoutes.PUT("/:id", categoryController.Update)
		categoryRoutes.DELETE("/:id", categoryController.Delete)
	}
	r.Run(":8080")
}
