package service

import (
	"log"

	"github.com/marloxxx/microservices-go/backend/category_service/dto"
	"github.com/marloxxx/microservices-go/backend/category_service/entity"
	"github.com/marloxxx/microservices-go/backend/category_service/repository"
	"github.com/mashingan/smapping"
)

// CategoryService is a contract about something that this service can do
type CategoryService interface {
	Insert(b dto.CategoryCreateDTO) entity.Category
	Update(b dto.CategoryUpdateDTO) entity.Category
	Delete(b entity.Category)
	All() []entity.Category
	FindByID(categoryID uint64) entity.Category
}

type categoryService struct {
	categoryRepository repository.CategoryRepository
}

// NewCategoryService creates a new instance of CategoryService
func NewCategoryService(categoryRepository repository.CategoryRepository) CategoryService {
	return &categoryService{
		categoryRepository: categoryRepository,
	}
}

func (service *categoryService) All() []entity.Category {
	return service.categoryRepository.All()
}

func (service *categoryService) FindByID(categoryID uint64) entity.Category {
	return service.categoryRepository.FindByID(categoryID)
}

func (service *categoryService) Insert(b dto.CategoryCreateDTO) entity.Category {
	category := entity.Category{}
	err := smapping.FillStruct(&category, smapping.MapFields(&b))
	if err != nil {
		log.Fatalf("Failed map %v: ", err)
	}
	res := service.categoryRepository.InsertCategory(category)
	return res
}

func (service *categoryService) Update(b dto.CategoryUpdateDTO) entity.Category {
	category := entity.Category{}
	err := smapping.FillStruct(&category, smapping.MapFields(&b))
	if err != nil {
		log.Fatalf("Failed map %v: ", err)
	}
	res := service.categoryRepository.UpdateCategory(category)
	return res
}

func (service *categoryService) Delete(b entity.Category) {
	service.categoryRepository.DeleteCategory(b)
}
