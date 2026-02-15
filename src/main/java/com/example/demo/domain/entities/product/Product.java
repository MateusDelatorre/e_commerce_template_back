package com.example.demo.domain.entities.product;

import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.math.BigDecimal;
import java.util.List;

@Data
@AllArgsConstructor
@NoArgsConstructor
public class Product {
    private Long id;
    private String description;
    private String name;
    private BigDecimal price;
    private Integer stockQuantity;
    private Category category;
    private List<Image> images;
    private Boolean active;
    private Double averageRating;

}
