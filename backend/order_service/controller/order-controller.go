package controller

import (
	"net/http"
	"strconv"

	"github.com/gin-gonic/gin"
	"github.com/marloxxx/microservices-go/backend/order_service/dto"
	"github.com/marloxxx/microservices-go/backend/order_service/entity"
	"github.com/marloxxx/microservices-go/backend/order_service/helper"
	"github.com/marloxxx/microservices-go/backend/order_service/service"
)

// OrderController is a contract about something that this controller can do
type OrderController interface {
	All(ctx *gin.Context)
	FindByID(ctx *gin.Context)
	Insert(ctx *gin.Context)
	Update(ctx *gin.Context)
	Delete(ctx *gin.Context)
}

type orderController struct {
	OrderService service.OrderService
}

// NewOrderController creates a new instance of AuthController
func NewOrderController(OrderService service.OrderService) OrderController {
	return &orderController{
		OrderService: OrderService,
	}
}

func (c *orderController) All(ctx *gin.Context) {
	userID, err := strconv.ParseUint(ctx.Query("user_id"), 0, 0)
	if err != nil {
		res := helper.BuildErrorResponse("Failed to get ID", "No param ID were found", helper.EmptyObj{})
		ctx.JSON(http.StatusBadRequest, res)
		return
	}
	orders := c.OrderService.All(userID)
	res := helper.BuildResponse(true, "OK!", orders)
	ctx.JSON(http.StatusOK, res)
}

func (c *orderController) FindByID(ctx *gin.Context) {
	id, err := strconv.ParseUint(ctx.Param("id"), 0, 0)
	if err != nil {
		res := helper.BuildErrorResponse("Failed to get ID", "No param ID were found", helper.EmptyObj{})
		ctx.JSON(http.StatusBadRequest, res)
		return
	}
	emptyOrder := entity.Order{}
	order := c.OrderService.FindByID(id)
	if order.ID == emptyOrder.ID {
		res := helper.BuildErrorResponse("Data not found", "No data with given ID", helper.EmptyObj{})
		ctx.JSON(http.StatusNotFound, res)
	} else {
		res := helper.BuildResponse(true, "OK!", order)
		ctx.JSON(http.StatusOK, res)
	}
}

func (c *orderController) Insert(ctx *gin.Context) {
	var orderCreateDTO dto.OrderCreateDTO
	if err := ctx.ShouldBindJSON(&orderCreateDTO); err != nil {
		res := helper.BuildErrorResponse("Failed to process request", err.Error(), helper.EmptyObj{})
		ctx.JSON(http.StatusBadRequest, res)
		return
	}
	result := c.OrderService.Insert(orderCreateDTO)
	response := helper.BuildResponse(true, "OK!", result)
	ctx.JSON(http.StatusCreated, response)
}

func (c *orderController) Update(ctx *gin.Context) {
	var orderUpdateDTO dto.OrderUpdateDTO
	if err := ctx.ShouldBindJSON(&orderUpdateDTO); err != nil {
		res := helper.BuildErrorResponse("Failed to process request", err.Error(), helper.EmptyObj{})
		ctx.JSON(http.StatusBadRequest, res)
		return
	}
	id, errID := strconv.ParseUint(ctx.Param("id"), 0, 0)
	if errID != nil {
		res := helper.BuildErrorResponse("Failed to get ID", "No param ID were found", helper.EmptyObj{})
		ctx.JSON(http.StatusBadRequest, res)
		return
	}
	orderUpdateDTO.ID = id
	result := c.OrderService.Update(orderUpdateDTO)
	response := helper.BuildResponse(true, "OK!", result)
	ctx.JSON(http.StatusOK, response)
}

func (c *orderController) Approve(ctx *gin.Context) {
	var order entity.Order
	id, errID := strconv.ParseUint(ctx.Param("id"), 0, 0)
	if errID != nil {
		res := helper.BuildErrorResponse("Failed to get ID", "No param ID were found", helper.EmptyObj{})
		ctx.JSON(http.StatusBadRequest, res)
		return
	}
	order.ID = id
	c.OrderService.Approve(order)
	res := helper.BuildResponse(true, "OK!", helper.EmptyObj{})
	ctx.JSON(http.StatusOK, res)
}

func (c *orderController) Reject(ctx *gin.Context) {
	var order entity.Order
	id, errID := strconv.ParseUint(ctx.Param("id"), 0, 0)
	if errID != nil {
		res := helper.BuildErrorResponse("Failed to get ID", "No param ID were found", helper.EmptyObj{})
		ctx.JSON(http.StatusBadRequest, res)
		return
	}
	order.ID = id
	c.OrderService.Reject(order)
	res := helper.BuildResponse(true, "OK!", helper.EmptyObj{})
	ctx.JSON(http.StatusOK, res)
}

func (c *orderController) Cancel(ctx *gin.Context) {
	var order entity.Order
	id, errID := strconv.ParseUint(ctx.Param("id"), 0, 0)
	if errID != nil {
		res := helper.BuildErrorResponse("Failed to get ID", "No param ID were found", helper.EmptyObj{})
		ctx.JSON(http.StatusBadRequest, res)
		return
	}

	order.ID = id
	c.OrderService.Cancel(order)
	res := helper.BuildResponse(true, "OK!", helper.EmptyObj{})
	ctx.JSON(http.StatusOK, res)
}

func (c *orderController) Delete(ctx *gin.Context) {
	var order entity.Order
	id, errID := strconv.ParseUint(ctx.Param("id"), 0, 0)
	if errID != nil {
		res := helper.BuildErrorResponse("Failed to get ID", "No param ID were found", helper.EmptyObj{})
		ctx.JSON(http.StatusBadRequest, res)
		return
	}
	order.ID = id
	c.OrderService.Delete(order)
	res := helper.BuildResponse(true, "Deleted", helper.EmptyObj{})
	ctx.JSON(http.StatusOK, res)
}
