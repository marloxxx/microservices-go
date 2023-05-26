package entity

type User struct {
	ID       uint64  `gorm:"primary_key:auto_increament" json:"id"`      // set primary key and auto increment untuk kolom id
	Name     string  `gorm:"type:varchar(255)" json:"name"`              // set type varchar(255) untuk kolom name
	Email    string  `gorm:"uniqueIndex;type:varchar(255)" json:"email"` // set unique index untuk kolom email
	Password string  `gorm:"->;<-;not null" json:"-"`                    // set not null untuk kolom password
	Token    string  `gorm:"-" json:"token,omitempty"`                   // set kolom token sebagai kolom yang tidak ada di database
	Books    *[]Book `json:"books,omitempty"`                            // set kolom books sebagai kolom yang tidak ada di database
}
