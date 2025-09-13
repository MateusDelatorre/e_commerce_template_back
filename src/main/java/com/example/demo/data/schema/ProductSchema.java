package com.example.demo.data.schema;

import com.example.demo.domain.entities.product.IProduct;
import com.example.demo.domain.entities.product.Product;
import jakarta.persistence.*;
import jakarta.validation.constraints.NotBlank;
import lombok.AllArgsConstructor;
import lombok.Builder;
import lombok.Data;
import lombok.NoArgsConstructor;
import org.hibernate.annotations.ColumnDefault;

import java.util.List;

@Entity
@Data // Generates getters, setters, toString, equals, and hashCode methods.
@NoArgsConstructor // Generates a no-args constructor.
@AllArgsConstructor // Generates a constructor with all arguments.
@Builder
@Table(name = "product_table")
public class ProductSchema implements IProduct {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @Column(nullable = true, length = 400, unique = false)
    private String description;
    @NotBlank
    @Column(nullable = false, length = 100, unique = false)
    private String name;
    private Double price;
    private Integer stockQuantity;
    private String category;
    @Column(nullable = false, unique = false)
    private String imageUrl;
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
        this.category = product.getCategory();
        this.imageUrl = product.getImageUrl();
        this.active = product.getActive();
        this.tags = product.getTags();
        this.reviews = product.getReviews();
        this.averageRating = product.getAverageRating();
        this.options = product.getOptions();
    }

    public Product toProduct() {
        return new Product(
            this.id,
            this.description,
            this.name,
            this.price,
            this.stockQuantity,
            this.category,
            this.imageUrl,
            this.active,
            this.tags,
            this.reviews,
            this.averageRating,
            this.options
        );
    }
}
