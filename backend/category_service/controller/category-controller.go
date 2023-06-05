package controller

import (
	"net/http"
	"strconv"

	"github.com/gin-gonic/gin"
	"github.com/marloxxx/microservices-go/backend/category_service/dto"
	"github.com/marloxxx/microservices-go/backend/category_service/entity"
	"github.com/marloxxx/microservices-go/backend/category_service/helper"
	"github.com/marloxxx/microservices-go/backend/category_service/service"
)

// CategoryController is a contract about something that this controller can do
type CategoryController interface {
	All(ctx *gin.Context)
	FindByID(ctx *gin.Context)
	Insert(ctx *gin.Context)
	Update(ctx *gin.Context)
	Delete(ctx *gin.Context)
}

type categoryController struct {
	categoryService service.CategoryService
}

// NewCategoryController creates a new instance of AuthController
func NewCategoryController(categoryService service.CategoryService) CategoryController {
	return &categoryController{
		categoryService: categoryService,
	}
}

func (c *categoryController) All(ctx *gin.Context) {
	var categories []entity.Category = c.categoryService.All()
	res := helper.BuildResponse(true, "OK!", categories)
	ctx.JSON(http.StatusOK, res)
}

func (c *categoryController) FindByID(ctx *gin.Context) {
	id, err := strconv.ParseUint(ctx.Param("id"), 0, 0)
	if err != nil {
		res := helper.BuildErrorResponse("Failed to get ID", "No param ID were found", helper.EmptyObj{})
		ctx.JSON(http.StatusBadRequest, res)
		return
	}
	category := c.categoryService.FindByID(id)
	if (category == entity.Category{}) {
		res := helper.BuildErrorResponse("Data not found", "No data with given ID", helper.EmptyObj{})
		ctx.JSON(http.StatusNotFound, res)
	} else {
		res := helper.BuildResponse(true, "OK!", category)
		ctx.JSON(http.StatusOK, res)
	}
}

func (c *categoryController) Insert(ctx *gin.Context) {
	var categoryCreateDTO dto.CategoryCreateDTO
	errDTO := ctx.ShouldBind(&categoryCreateDTO)
	if errDTO != nil {
		res := helper.BuildErrorResponse("Failed to process request", errDTO.Error(), helper.EmptyObj{})
		ctx.JSON(http.StatusBadRequest, res)
		return
	}
	result := c.categoryService.Insert(categoryCreateDTO)
	response := helper.BuildResponse(true, "OK!", result)
	ctx.JSON(http.StatusCreated, response)
}

func (c *categoryController) Update(ctx *gin.Context) {
	var categoryUpdateDTO dto.CategoryUpdateDTO
	errDTO := ctx.ShouldBind(&categoryUpdateDTO)
	if errDTO != nil {
		res := helper.BuildErrorResponse("Failed to process request", errDTO.Error(), helper.EmptyObj{})
		ctx.JSON(http.StatusBadRequest, res)
		return
	}
	id, errID := strconv.ParseUint(ctx.Param("id"), 0, 0)
	if errID != nil {
		res := helper.BuildErrorResponse("Failed to get ID", "No param ID were found", helper.EmptyObj{})
		ctx.JSON(http.StatusBadRequest, res)
		return
	}
	categoryUpdateDTO.ID = id
	result := c.categoryService.Update(categoryUpdateDTO)
	response := helper.BuildResponse(true, "OK!", result)
	ctx.JSON(http.StatusOK, response)
}

func (c *categoryController) Delete(ctx *gin.Context) {
	var category entity.Category
	id, errID := strconv.ParseUint(ctx.Param("id"), 0, 0)
	if errID != nil {
		res := helper.BuildErrorResponse("Failed to get ID", "No param ID were found", helper.EmptyObj{})
		ctx.JSON(http.StatusBadRequest, res)
		return
	}
	category.ID = id
	c.categoryService.Delete(category)
	res := helper.BuildResponse(true, "Deleted", helper.EmptyObj{})
	ctx.JSON(http.StatusOK, res)
}
