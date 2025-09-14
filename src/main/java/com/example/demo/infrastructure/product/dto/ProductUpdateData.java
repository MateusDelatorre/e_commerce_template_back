package com.example.demo.infrastructure.product.dto;

import com.example.demo.domain.entities.product.Category;
import com.example.demo.domain.usecase.product.dto.IProductUpdateData;
import lombok.AllArgsConstructor;
import lombok.Builder;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.math.BigDecimal;

@Data // Generates getters, setters, toString, equals, and hashCode methods.
@NoArgsConstructor // Generates a no-args constructor.
@AllArgsConstructor // Generates a constructor with all arguments.
@Builder
public class ProductUpdateData implements IProductUpdateData {
    private Long id;
    private String description;
    private String name;
    private BigDecimal price;
    private Integer stockQuantity;
    private Category category;
    private Boolean active;
}
