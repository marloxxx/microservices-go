package entity

type Cart struct {
	ID           uint64 `gorm:"primary_key:auto_increment"`
	ProductID    uint64 `gorm:"type:int(11)"`
	ProductName  string `gorm:"type:varchar(255)"`
	ProductImage string `gorm:"type:varchar(255)"`
	Quantity     uint64 `gorm:"type:int(11)"`
	Price        uint64 `gorm:"type:int(11)"`
	Total        uint64 `gorm:"type:int(11)"`
	UserID       uint64 `gorm:"type:int(11)"`
}
