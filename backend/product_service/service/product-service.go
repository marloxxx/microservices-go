package service

import (
	"log"

	"github.com/marloxxx/microservices-go/backend/product_service/dto"
	"github.com/marloxxx/microservices-go/backend/product_service/entity"
	"github.com/marloxxx/microservices-go/backend/product_service/repository"
	"github.com/mashingan/smapping"
)

// ProductService is a contract about something that this service can do
type ProductService interface {
	Insert(b dto.ProductCreateDTO) entity.Product
	Update(b dto.ProductUpdateDTO) entity.Product
	Delete(b entity.Product)
	All() []entity.Product
	FindByID(productID uint64) entity.Product
}

type productService struct {
	productRepository repository.ProductRepository
}

// NewProductService creates a new instance of ProductService
func NewProductService(productRepository repository.ProductRepository) ProductService {
	return &productService{
		productRepository: productRepository,
	}
}

func (service *productService) All() []entity.Product {
	return service.productRepository.All()
}

func (service *productService) FindByID(productID uint64) entity.Product {
	return service.productRepository.FindByID(productID)
}

func (service *productService) Insert(b dto.ProductCreateDTO) entity.Product {
	product := entity.Product{}
	err := smapping.FillStruct(&product, smapping.MapFields(&b))
	if err != nil {
		log.Fatalf("Failed map %v", err)
	}

	res := service.productRepository.InsertProduct(product)
	return res
}

func (service *productService) Update(b dto.ProductUpdateDTO) entity.Product {
	product := entity.Product{}
	err := smapping.FillStruct(&product, smapping.MapFields(&b))
	if err != nil {
		log.Fatalf("Failed map %v", err)
	}

	res := service.productRepository.UpdateProduct(product)
	return res
}

func (service *productService) Delete(b entity.Product) {
	service.productRepository.DeleteProduct(b)
}
