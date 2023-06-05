package main

import (
	"github.com/gin-gonic/gin"
	"github.com/marloxxx/microservices-go/backend/product_service/config"
	"github.com/marloxxx/microservices-go/backend/product_service/controller"
	"github.com/marloxxx/microservices-go/backend/product_service/repository"
	"github.com/marloxxx/microservices-go/backend/product_service/service"
	"gorm.io/gorm"
)

var (
	db                *gorm.DB                     = config.SetupDatabaseConnection()
	productRepository repository.ProductRepository = repository.NewProductRepository(db)
	ProductService    service.ProductService       = service.NewProductService(productRepository)
	productController controller.ProductController = controller.NewProductController(ProductService)
)

// membuat variable db dengan nilai setup database connection
func main() {
	defer config.CloseDatabaseConnection(db)
	r := gin.Default()

	productRoutes := r.Group("/api/products")
	{
		productRoutes.GET("/", productController.All)
		productRoutes.POST("/", productController.Insert)
		productRoutes.GET("/:id", productController.FindByID)
		productRoutes.PUT("/:id", productController.Update)
		productRoutes.DELETE("/:id", productController.Delete)
	}
	r.Run(":8081")
}
