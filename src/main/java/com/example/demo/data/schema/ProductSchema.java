package com.example.demo.data.schema;

import com.example.demo.domain.entities.product.Product;
import jakarta.persistence.*;
import jakarta.validation.constraints.NotBlank;
import lombok.AllArgsConstructor;
import lombok.Builder;
import lombok.Data;
import lombok.NoArgsConstructor;
import org.hibernate.annotations.ColumnDefault;

import java.math.BigDecimal;
import java.util.ArrayList;
import java.util.List;

@Entity
@Data // Generates getters, setters, toString, equals, and hashCode methods.
@NoArgsConstructor // Generates a no-args constructor.
@AllArgsConstructor // Generates a constructor with all arguments.
@Builder
@Table(name = "product")
public class ProductSchema {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @Column(nullable = true, length = 400, unique = false)
    private String description;
    @NotBlank
    @Column(nullable = false, length = 100, unique = false)
    private String name;
    private BigDecimal price;
    private Integer stockQuantity;

    @ManyToOne(cascade = CascadeType.ALL)
    @JoinColumn(name = "category_id")
    private CategorySchema category;

    @OneToMany(mappedBy = "product", cascade = CascadeType.ALL, orphanRemoval = true)
    private List<ImageSchema> images;
    @ColumnDefault("false")
    private Boolean active;

    private java.util.List<String> tags;
    private java.util.List<String> reviews;
    private Double averageRating;
    private java.util.List<String> options;

    public ProductSchema(Product product) {
        this.id = product.getId();
        this.description = product.getDescription();
        this.name = product.getName();
        this.price = product.getPrice();
        this.stockQuantity = product.getStockQuantity();
        this.category = new CategorySchema(product.getCategory());
        this.images = product.getImages().stream().map(ImageSchema::new).toList();
        this.active = product.getActive();
        this.averageRating = product.getAverageRating();
    }

    public Product toProduct() {
        return new Product(
            this.id,
            this.description,
            this.name,
            this.price,
            this.stockQuantity,
            this.category.toCategory(),
            this.images.stream().map(ImageSchema::toImage).toList(),
            this.active,
            this.averageRating
        );
    }
}
