package com.example.demo.infrastructure.product.dto;

import com.example.demo.domain.usecase.product.dto.IProductRegistrationData;
import lombok.AllArgsConstructor;
import lombok.Builder;
import lombok.Data;
import lombok.NoArgsConstructor;

@Data // Generates getters, setters, toString, equals, and hashCode methods.
@NoArgsConstructor // Generates a no-args constructor.
@AllArgsConstructor // Generates a constructor with all arguments.
@Builder
public class ProductRegistrationData implements IProductRegistrationData {
    private String description;
    private String name;
    private Double price;
    private Integer stockQuantity;
    private String category;
    private String imageUrl;
    private Boolean active;
    private java.util.List<String> tags;
    private java.util.List<String> options;
}
