package middleware

import (
	"log"
	"net/http"

	"github.com/dgrijalva/jwt-go"
	"github.com/gin-gonic/gin"
	"github.com/marloxxx/golang_gin_gorm_GWT/helper"
	"github.com/marloxxx/golang_gin_gorm_GWT/service"
)

// AuthorizeJWT validates the token user given, return 401 if not valid
func AuthorizeJWT(jwtService service.JWTService) gin.HandlerFunc { // method authorizeJWT dengan parameter jwt service
	return func(c *gin.Context) {
		authHeader := c.GetHeader("Authorization") // ambil value dari header Authorization
		if authHeader == "" { // jika header kosong maka abort
			response := helper.BuildErrorResponse("Failed to process request", "no token found", nil) // membuat response error
			c.AbortWithStatusJSON(http.StatusBadRequest, response) 							   // abort dengan status bad request
			return
		}
		token, err := jwtService.ValidateToken(authHeader) // memanggil method validate token pada jwt service
		if token.Valid { // jika token valid maka
			claims := token.Claims.(jwt.MapClaims) // mengambil value dari token
			log.Println("claim[user_id] : ", claims["user_id"]) // menampilkan value dari token
			log.Println("Claim[issuer] :", claims["issuer"]) // menampilkan value dari token
		} else {
			log.Println(err) // menampilkan pesan error
			response := helper.BuildErrorResponse("Token is not valid", err.Error(), nil) // membuat response error
			c.AbortWithStatusJSON(http.StatusUnauthorized, response) 					  // abort dengan status unauthorized
		}
	}
}
