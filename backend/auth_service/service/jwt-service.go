package service

import (
	"fmt"
	"os"
	"time"

	"github.com/dgrijalva/jwt-go"
)

type JWTService interface {
	GenerateToken(userID string) string
	ValidateToken(token string) (*jwt.Token, error)
}
// membuat interface JWTService dengan method GenerateToken dan ValidateToken

type jwtCustomClaim struct {
	UserID string `json:"user_id"`
	jwt.StandardClaims
}
// membuat struct jwtCustomClaim dengan field UserID dan StandardClaims

type jwtService struct {
	secretKey string
	issuer    string
}
// membuat struct jwtService dengan field secretKey dan issuer

// NewJWTService methode is creates a new instance of JWTService
func NewJWTService() JWTService { // membuat method NewJWTService
	return &jwtService{
		issuer:    "marloxxx",
		secretKey: getSecretKey(),
	}
}

func getSecretKey() string { // membuat method getSecretKey
	secretKey := os.Getenv("JWT_SECRET") // mengambil value dari environment variable JWT_SECRET
	if secretKey != "" { // jika secretKey tidak kosong maka
		secretKey = "marloxxx"
	}
	return secretKey // mengembalikan nilai secretKey
}

func (j *jwtService) GenerateToken(UserID string) string { // membuat method GenerateToken dengan parameter UserID
	claims := &jwtCustomClaim{ // membuat variable claims dengan type jwtCustomClaim
		UserID, // isi field UserID dengan parameter UserID
		jwt.StandardClaims{ // isi field StandardClaims dengan
			ExpiresAt: time.Now().AddDate(1, 0, 0).Unix(),
			Issuer:    j.issuer,
			IssuedAt:  time.Now().Unix(),
		},
	}
	token := jwt.NewWithClaims(jwt.SigningMethodHS256, claims) // membuat variable token dengan type jwt dengan method NewWithClaims
	t, err := token.SignedString([]byte(j.secretKey)) // membuat variable t dengan type string dan err dengan type error
	if err != nil { // jika err tidak kosong maka
		panic(err) // panic
	}
	return t // mengembalikan nilai t
}

func (j *jwtService) ValidateToken(token string) (*jwt.Token, error) { // membuat method ValidateToken dengan parameter token
	return jwt.Parse(token, func(t_ *jwt.Token) (interface{}, error) { // membuat variable t_ dengan type jwt.Token dan mengembalikan nilai interface dan error
		if _, ok := t_.Method.(*jwt.SigningMethodHMAC); !ok { // jika t_.Method tidak sama dengan jwt.SigningMethodHMAC maka
			return nil, fmt.Errorf("Unexpected signing method %v", t_.Header["alg"]) // mengembalikan nilai nil dan error
		}
		return []byte(j.secretKey), nil // mengembalikan nilai []byte dan nil
	})
}
