package controller

import (
	"net/http"
	"strconv"

	"github.com/gin-gonic/gin"
	"github.com/marloxxx/microservices-go/backend/auth_service/dto"
	"github.com/marloxxx/microservices-go/backend/auth_service/entity"
	"github.com/marloxxx/microservices-go/backend/auth_service/helper"
	"github.com/marloxxx/microservices-go/backend/auth_service/service"
)

// AuthController interface is a contract what this controller can do
// kita menggunkan interface pada bagian ini
type AuthController interface { // mengatur aksi yang dilakukan oleh authentikasi yaitu
	Login(ctx *gin.Context)    //aksi login
	Register(ctx *gin.Context) //aksi register
}

type authController struct {
	authService service.AuthService // use auth service
	jwtService  service.JWTService  // use auth from JWT
}

func NewAuthController(authService service.AuthService, jwtService service.JWTService) AuthController { // method dengan parameter auth JWT
	return &authController{
		authService: authService,
		jwtService:  jwtService,
	}
}

func (c *authController) Login(ctx *gin.Context) { // method login
	var loginDTO dto.LoginDTO // mengarahkan login pada file dto user
	errDTO := ctx.ShouldBind(&loginDTO)
	if errDTO != nil {
		response := helper.BuildErrorResponse("Failed to process request", errDTO.Error(), helper.EmptyObj{})
		ctx.AbortWithStatusJSON(http.StatusBadRequest, response)
		return
	}
	authResult := c.authService.VerifyCredential(loginDTO.Email, loginDTO.Password) // login membutuhkan email dan password
	if v, ok := authResult.(entity.User); ok {
		generatedToken := c.jwtService.GenerateToken(strconv.FormatUint(v.ID, 10)) // melakukan generate token random untuk akun yang telah didaftar yang nanntinya akan digunakan untuk melakukan aksi
		v.Token = generatedToken                                                   // create token
		response := helper.BuildResponse(true, "OK!", v)
		ctx.JSON(http.StatusOK, response)
		return
	}
	// jika email dan password tidak sesuai maka akan menampilkan pesan error invalid credential
	response := helper.BuildErrorResponse("Please check again your credential", "Invalid Credential", helper.EmptyObj{})
	ctx.AbortWithStatusJSON(http.StatusUnauthorized, response) // status unauthorize
}

func (c *authController) Register(ctx *gin.Context) { // method register
	var registerDTO dto.RegisterDTO // dto register
	errDTO := ctx.ShouldBind(&registerDTO)
	if errDTO != nil {
		response := helper.BuildErrorResponse("Failed to process request", errDTO.Error(), helper.EmptyObj{})
		ctx.AbortWithStatusJSON(http.StatusBadRequest, response)
		return
	}
	if !c.authService.IsDuplicateEmail(registerDTO.Email) { // menggecek apakah email adalah duplikat
		response := helper.BuildErrorResponse("Failed to process request", "Duplicate email", helper.EmptyObj{})
		ctx.JSON(http.StatusConflict, response)
	} else {
		createdUser := c.authService.CreateUser(registerDTO)
		token := c.jwtService.GenerateToken(strconv.FormatUint(createdUser.ID, 10)) // generate token untuk akun yang telah didaftarkan
		createdUser.Token = token
		response := helper.BuildResponse(true, "OK!", createdUser)
		ctx.JSON(http.StatusCreated, response)
	}
}
