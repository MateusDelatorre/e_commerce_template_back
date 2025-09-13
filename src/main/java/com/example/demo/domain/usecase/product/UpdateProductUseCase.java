package com.example.demo.domain.usecase.product;

import com.example.demo.domain.entities.product.IProductRepository;
import com.example.demo.domain.entities.product.Product;
import com.example.demo.domain.usecase.product.dto.IProductRegistrationData;
import com.example.demo.domain.usecase.product.dto.IProductUpdateData;

public class UpdateProductUseCase {
    private final IProductRepository productRepository;

    public UpdateProductUseCase(IProductRepository productRepository) {
        this.productRepository = productRepository;
    }

    public Product execute(IProductUpdateData data) {
        Product product = productRepository.findById(data.getId());
        if (product == null) {
            throw new IllegalArgumentException("Product with ID " + data.getId() + " not found.");
        }
        product.setDescription(data.getDescription());
        product.setName(data.getName());
        product.setPrice(data.getPrice());
        product.setStockQuantity(data.getStockQuantity());
        product.setCategory(data.getCategory());
        product.setImageUrl(data.getImageUrl());
        product.setActive(data.getActive());
        product.setTags(data.getTags());
        product.setOptions(data.getOptions());
        return productRepository.save(product);
    }
}
