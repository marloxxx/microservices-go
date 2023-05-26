package controller

import (
	"fmt"
	"net/http"
	"strconv"

	"github.com/dgrijalva/jwt-go"
	"github.com/gin-gonic/gin"
	"github.com/marloxxx/golang_gin_gorm_GWT/dto"
	"github.com/marloxxx/golang_gin_gorm_GWT/helper"
	"github.com/marloxxx/golang_gin_gorm_GWT/service"
)

// membuat interface untuk controller user
type UserController interface {
	Update(context *gin.Context)
	Profile(context *gin.Context)
}

type userController struct { // membuat struct user controller
	userService service.UserService
	jwtService  service.JWTService
}

// NewUserController is creating new instance of UserController
func NewUserController(userService service.UserService, jwtService service.JWTService) UserController { // method dengan parameter auth JWT
	return &userController{
		userService: userService,
		jwtService:  jwtService,
	}
}

func (c *userController) Update(context *gin.Context) { // method update untuk mengupdate data user
	var userUpdateDTO dto.UserUpdateDTO // membuat objek user update dto
	errDTO := context.ShouldBind(&userUpdateDTO) // memanggil method should bind pada context untuk memasukkan data dari form ke objek user update dto
	if errDTO != nil { // jika error maka abort
		res := helper.BuildErrorResponse("Failed to process request", errDTO.Error(), helper.EmptyObj{}) // membuat response error
		context.AbortWithStatusJSON(http.StatusBadRequest, res) 									 // abort dengan status bad request
		return
	}
	authHeader := context.GetHeader("Authorization") // ambil value dari header Authorization
	token, errToken := c.jwtService.ValidateToken(authHeader) // memanggil method validate token pada jwt service
	if errToken != nil { // jika error maka panic
		panic(errToken.Error()) // menampilkan pesan error
	}
	claims := token.Claims.(jwt.MapClaims) // mengambil value dari token
	id, err := strconv.ParseUint(fmt.Sprintf("%v", claims["user_id"]), 10, 64) // mengubah value dari token ke uint64
	if err != nil { // jika error maka panic
		panic(err.Error()) // menampilkan pesan error
	}
	userUpdateDTO.ID = id // mengisi value id pada user update dto dengan id yang diambil dari token
	u := c.userService.Update(userUpdateDTO) // memanggil method update pada user service
	res := helper.BuildResponse(true, "OK!", u) // membuat response
	context.JSON(http.StatusOK, res) // menampilkan response
}

func (c *userController) Profile(context *gin.Context) { // method profile untuk menampilkan data user
	authHeader := context.GetHeader("Authorization") // ambil value dari header Authorization
	token, errToken := c.jwtService.ValidateToken(authHeader) // memanggil method validate token pada jwt service
	if errToken != nil { // jika error maka panic
		panic(errToken.Error()) // menampilkan pesan error
	}
	claims := token.Claims.(jwt.MapClaims) // mengambil value dari token
	id := fmt.Sprintf("%v", claims["user_id"]) // mengubah value dari token ke string
	user := c.userService.Profile(id) // memanggil method profile pada user service
	res := helper.BuildResponse(true, "OK!", user) // membuat response
	context.JSON(http.StatusOK, res) // menampilkan response
}
